<?php

namespace _PhpScopera143bcca66cb\React\Http\Io;

use _PhpScopera143bcca66cb\Evenement\EventEmitter;
use _PhpScopera143bcca66cb\React\Stream\ReadableStreamInterface;
use _PhpScopera143bcca66cb\React\Stream\Util;
use _PhpScopera143bcca66cb\React\Stream\WritableStreamInterface;
use Exception;
/**
 * [Internal] Decodes "Transfer-Encoding: chunked" from given stream and returns only payload data.
 *
 * This is used internally to decode incoming requests with this encoding.
 *
 * @internal
 */
class ChunkedDecoder extends \_PhpScopera143bcca66cb\Evenement\EventEmitter implements \_PhpScopera143bcca66cb\React\Stream\ReadableStreamInterface
{
    const CRLF = "\r\n";
    const MAX_CHUNK_HEADER_SIZE = 1024;
    private $closed = \false;
    private $input;
    private $buffer = '';
    private $chunkSize = 0;
    private $transferredSize = 0;
    private $headerCompleted = \false;
    public function __construct(\_PhpScopera143bcca66cb\React\Stream\ReadableStreamInterface $input)
    {
        $this->input = $input;
        $this->input->on('data', array($this, 'handleData'));
        $this->input->on('end', array($this, 'handleEnd'));
        $this->input->on('error', array($this, 'handleError'));
        $this->input->on('close', array($this, 'close'));
    }
    public function isReadable()
    {
        return !$this->closed && $this->input->isReadable();
    }
    public function pause()
    {
        $this->input->pause();
    }
    public function resume()
    {
        $this->input->resume();
    }
    public function pipe(\_PhpScopera143bcca66cb\React\Stream\WritableStreamInterface $dest, array $options = array())
    {
        \_PhpScopera143bcca66cb\React\Stream\Util::pipe($this, $dest, $options);
        return $dest;
    }
    public function close()
    {
        if ($this->closed) {
            return;
        }
        $this->buffer = '';
        $this->closed = \true;
        $this->input->close();
        $this->emit('close');
        $this->removeAllListeners();
    }
    /** @internal */
    public function handleEnd()
    {
        if (!$this->closed) {
            $this->handleError(new \Exception('Unexpected end event'));
        }
    }
    /** @internal */
    public function handleError(\Exception $e)
    {
        $this->emit('error', array($e));
        $this->close();
    }
    /** @internal */
    public function handleData($data)
    {
        $this->buffer .= $data;
        while ($this->buffer !== '') {
            if (!$this->headerCompleted) {
                $positionCrlf = \strpos($this->buffer, static::CRLF);
                if ($positionCrlf === \false) {
                    // Header shouldn't be bigger than 1024 bytes
                    if (isset($this->buffer[static::MAX_CHUNK_HEADER_SIZE])) {
                        $this->handleError(new \Exception('Chunk header size inclusive extension bigger than' . static::MAX_CHUNK_HEADER_SIZE . ' bytes'));
                    }
                    return;
                }
                $header = \strtolower((string) \substr($this->buffer, 0, $positionCrlf));
                $hexValue = $header;
                if (\strpos($header, ';') !== \false) {
                    $array = \explode(';', $header);
                    $hexValue = $array[0];
                }
                if ($hexValue !== '') {
                    $hexValue = \ltrim(\trim($hexValue), "0");
                    if ($hexValue === '') {
                        $hexValue = "0";
                    }
                }
                $this->chunkSize = @\hexdec($hexValue);
                if (!\is_int($this->chunkSize) || \dechex($this->chunkSize) !== $hexValue) {
                    $this->handleError(new \Exception($hexValue . ' is not a valid hexadecimal number'));
                    return;
                }
                $this->buffer = (string) \substr($this->buffer, $positionCrlf + 2);
                $this->headerCompleted = \true;
                if ($this->buffer === '') {
                    return;
                }
            }
            $chunk = (string) \substr($this->buffer, 0, $this->chunkSize - $this->transferredSize);
            if ($chunk !== '') {
                $this->transferredSize += \strlen($chunk);
                $this->emit('data', array($chunk));
                $this->buffer = (string) \substr($this->buffer, \strlen($chunk));
            }
            $positionCrlf = \strpos($this->buffer, static::CRLF);
            if ($positionCrlf === 0) {
                if ($this->chunkSize === 0) {
                    $this->emit('end');
                    $this->close();
                    return;
                }
                $this->chunkSize = 0;
                $this->headerCompleted = \false;
                $this->transferredSize = 0;
                $this->buffer = (string) \substr($this->buffer, 2);
            } elseif ($this->chunkSize === 0) {
                // end chunk received, skip all trailer data
                $this->buffer = (string) \substr($this->buffer, $positionCrlf);
            }
            if ($positionCrlf !== 0 && $this->chunkSize !== 0 && $this->chunkSize === $this->transferredSize && \strlen($this->buffer) > 2) {
                // the first 2 characters are not CRLF, send error event
                $this->handleError(new \Exception('Chunk does not end with a CRLF'));
                return;
            }
            if ($positionCrlf !== 0 && \strlen($this->buffer) < 2) {
                // No CRLF found, wait for additional data which could be a CRLF
                return;
            }
        }
    }
}
