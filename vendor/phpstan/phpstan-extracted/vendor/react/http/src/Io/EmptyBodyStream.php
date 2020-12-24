<?php

namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Http\Io;

use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Evenement\EventEmitter;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Psr\Http\Message\StreamInterface;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Stream\ReadableStreamInterface;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Stream\Util;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Stream\WritableStreamInterface;
/**
 * [Internal] Bridge between an empty StreamInterface from PSR-7 and ReadableStreamInterface from ReactPHP
 *
 * This class is used in the server to represent an empty body stream of an
 * incoming response from the client. This is similar to the `HttpBodyStream`,
 * but is specifically designed for the common case of having an empty message
 * body.
 *
 * Note that this is an internal class only and nothing you should usually care
 * about. See the `StreamInterface` and `ReadableStreamInterface` for more
 * details.
 *
 * @see HttpBodyStream
 * @see StreamInterface
 * @see ReadableStreamInterface
 * @internal
 */
class EmptyBodyStream extends \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Evenement\EventEmitter implements \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Psr\Http\Message\StreamInterface, \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Stream\ReadableStreamInterface
{
    private $closed = \false;
    public function isReadable()
    {
        return !$this->closed;
    }
    public function pause()
    {
        // NOOP
    }
    public function resume()
    {
        // NOOP
    }
    public function pipe(\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Stream\WritableStreamInterface $dest, array $options = array())
    {
        \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\React\Stream\Util::pipe($this, $dest, $options);
        return $dest;
    }
    public function close()
    {
        if ($this->closed) {
            return;
        }
        $this->closed = \true;
        $this->emit('close');
        $this->removeAllListeners();
    }
    public function getSize()
    {
        return 0;
    }
    /** @ignore */
    public function __toString()
    {
        return '';
    }
    /** @ignore */
    public function detach()
    {
        return null;
    }
    /** @ignore */
    public function tell()
    {
        throw new \BadMethodCallException();
    }
    /** @ignore */
    public function eof()
    {
        throw new \BadMethodCallException();
    }
    /** @ignore */
    public function isSeekable()
    {
        return \false;
    }
    /** @ignore */
    public function seek($offset, $whence = \SEEK_SET)
    {
        throw new \BadMethodCallException();
    }
    /** @ignore */
    public function rewind()
    {
        throw new \BadMethodCallException();
    }
    /** @ignore */
    public function isWritable()
    {
        return \false;
    }
    /** @ignore */
    public function write($string)
    {
        throw new \BadMethodCallException();
    }
    /** @ignore */
    public function read($length)
    {
        throw new \BadMethodCallException();
    }
    /** @ignore */
    public function getContents()
    {
        return '';
    }
    /** @ignore */
    public function getMetadata($key = null)
    {
        return $key === null ? array() : null;
    }
}