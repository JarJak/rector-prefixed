<?php

namespace _PhpScoper26e51eeacccf\React\Promise\Stream;

use _PhpScoper26e51eeacccf\Evenement\EventEmitter;
use InvalidArgumentException;
use _PhpScoper26e51eeacccf\React\Promise\CancellablePromiseInterface;
use _PhpScoper26e51eeacccf\React\Promise\PromiseInterface;
use _PhpScoper26e51eeacccf\React\Stream\ReadableStreamInterface;
use _PhpScoper26e51eeacccf\React\Stream\Util;
use _PhpScoper26e51eeacccf\React\Stream\WritableStreamInterface;
/**
 * @internal
 * @see unwrapReadable() instead
 */
class UnwrapReadableStream extends \_PhpScoper26e51eeacccf\Evenement\EventEmitter implements \_PhpScoper26e51eeacccf\React\Stream\ReadableStreamInterface
{
    private $promise;
    private $closed = \false;
    /**
     * Instantiate new unwrapped readable stream for given `Promise` which resolves with a `ReadableStreamInterface`.
     *
     * @param PromiseInterface $promise Promise<ReadableStreamInterface, Exception>
     */
    public function __construct(\_PhpScoper26e51eeacccf\React\Promise\PromiseInterface $promise)
    {
        $out = $this;
        $closed =& $this->closed;
        $this->promise = $promise->then(function ($stream) {
            if (!$stream instanceof \_PhpScoper26e51eeacccf\React\Stream\ReadableStreamInterface) {
                throw new \InvalidArgumentException('Not a readable stream');
            }
            return $stream;
        })->then(function (\_PhpScoper26e51eeacccf\React\Stream\ReadableStreamInterface $stream) use($out, &$closed) {
            // stream is already closed, make sure to close output stream
            if (!$stream->isReadable()) {
                $out->close();
                return $stream;
            }
            // resolves but output is already closed, make sure to close stream silently
            if ($closed) {
                $stream->close();
                return $stream;
            }
            // stream any writes into output stream
            $stream->on('data', function ($data) use($out) {
                $out->emit('data', array($data, $out));
            });
            // forward end events and close
            $stream->on('end', function () use($out, &$closed) {
                if (!$closed) {
                    $out->emit('end', array($out));
                    $out->close();
                }
            });
            // error events cancel output stream
            $stream->on('error', function ($error) use($out) {
                $out->emit('error', array($error, $out));
                $out->close();
            });
            // close both streams once either side closes
            $stream->on('close', array($out, 'close'));
            $out->on('close', array($stream, 'close'));
            return $stream;
        }, function ($e) use($out, &$closed) {
            if (!$closed) {
                $out->emit('error', array($e, $out));
                $out->close();
            }
            // resume() and pause() may attach to this promise, so ensure we actually reject here
            throw $e;
        });
    }
    public function isReadable()
    {
        return !$this->closed;
    }
    public function pause()
    {
        if ($this->promise !== null) {
            $this->promise->then(function (\_PhpScoper26e51eeacccf\React\Stream\ReadableStreamInterface $stream) {
                $stream->pause();
            });
        }
    }
    public function resume()
    {
        if ($this->promise !== null) {
            $this->promise->then(function (\_PhpScoper26e51eeacccf\React\Stream\ReadableStreamInterface $stream) {
                $stream->resume();
            });
        }
    }
    public function pipe(\_PhpScoper26e51eeacccf\React\Stream\WritableStreamInterface $dest, array $options = array())
    {
        \_PhpScoper26e51eeacccf\React\Stream\Util::pipe($this, $dest, $options);
        return $dest;
    }
    public function close()
    {
        if ($this->closed) {
            return;
        }
        $this->closed = \true;
        // try to cancel promise once the stream closes
        if ($this->promise instanceof \_PhpScoper26e51eeacccf\React\Promise\CancellablePromiseInterface) {
            $this->promise->cancel();
        }
        $this->promise = null;
        $this->emit('close');
        $this->removeAllListeners();
    }
}
