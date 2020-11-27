<?php

namespace _PhpScoper006a73f0e455\Generics\Bug2622;

/**
 * @template TValue
 * @template-implements \IteratorAggregate<int,TValue>
 */
class MyArray implements \IteratorAggregate
{
    /** @var array<int,TValue> */
    private $values = [];
    public function __construct()
    {
        $this->values = [];
    }
    public function getIterator()
    {
        return new \ArrayObject($this->values);
    }
}
