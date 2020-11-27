<?php

namespace _PhpScoper26e51eeacccf;

class ArrayIterator implements \SeekableIterator, \ArrayAccess, \Serializable, \Countable
{
    public function __construct(array|object $array = [], int $flags = 0)
    {
    }
    /**
     * @param string|int $key
     * @return bool
     * @implementation-alias ArrayObject::offsetExists
     */
    public function offsetExists($key)
    {
    }
    /**
     * @param string|int $key
     * @return mixed
     * @implementation-alias ArrayObject::offsetGet
     */
    public function offsetGet($key)
    {
    }
    /**
     * @param string|int $key
     * @return void
     * @implementation-alias ArrayObject::offsetSet
     */
    public function offsetSet($key, mixed $value)
    {
    }
    /**
     * @param string|int $key
     * @return void
     * @implementation-alias ArrayObject::offsetUnset
     */
    public function offsetUnset($key)
    {
    }
    /**
     * @return void
     * @implementation-alias ArrayObject::append
     */
    public function append(mixed $value)
    {
    }
    /**
     * @return array
     * @implementation-alias ArrayObject::getArrayCopy
     */
    public function getArrayCopy()
    {
    }
    /**
     * @return int
     * @implementation-alias ArrayObject::count
     */
    public function count()
    {
    }
    /**
     * @return int
     * @implementation-alias ArrayObject::getFlags
     */
    public function getFlags()
    {
    }
    /**
     * @return void
     * @implementation-alias ArrayObject::setFlags
     */
    public function setFlags(int $flags)
    {
    }
    /**
     * @return bool
     * @implementation-alias ArrayObject::asort
     */
    public function asort(int $flags = \SORT_REGULAR)
    {
    }
    /**
     * @return bool
     * @implementation-alias ArrayObject::ksort
     */
    public function ksort(int $flags = \SORT_REGULAR)
    {
    }
    /**
     * @return bool
     * @implementation-alias ArrayObject::uasort
     */
    public function uasort(callable $callback)
    {
    }
    /**
     * @return bool
     * @implementation-alias ArrayObject::uksort
     */
    public function uksort(callable $callback)
    {
    }
    /**
     * @return bool
     * @implementation-alias ArrayObject::natsort
     */
    public function natsort()
    {
    }
    /**
     * @return bool
     * @implementation-alias ArrayObject::natcasesort
     */
    public function natcasesort()
    {
    }
    /**
     * @return void
     * @implementation-alias ArrayObject::unserialize
     */
    public function unserialize(string $data)
    {
    }
    /**
     * @return string
     * @implementation-alias ArrayObject::serialize
     */
    public function serialize()
    {
    }
    /**
     * @return array
     * @implementation-alias ArrayObject::__serialize
     */
    public function __serialize()
    {
    }
    /**
     * @return void
     * @implementation-alias ArrayObject::__unserialize
     */
    public function __unserialize(array $data)
    {
    }
    /** @return void */
    public function rewind()
    {
    }
    /** @return mixed */
    public function current()
    {
    }
    /** @return mixed */
    public function key()
    {
    }
    /** @return void */
    public function next()
    {
    }
    /** @return bool */
    public function valid()
    {
    }
    /** @return void */
    public function seek(int $offset)
    {
    }
    /**
     * @return array
     * @implementation-alias ArrayObject::__debugInfo
     */
    public function __debugInfo()
    {
    }
}
\class_alias('_PhpScoper26e51eeacccf\\ArrayIterator', 'ArrayIterator', \false);
