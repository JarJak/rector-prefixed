<?php

namespace _PhpScoper006a73f0e455\TestIntersectionTypeIsSupertypeOf;

use ArrayAccess;
use Countable;
use IteratorAggregate;
/**
 * @psalm-template TKey of array-key
 * @psalm-template T
 * @template-extends IteratorAggregate<TKey, T>
 * @template-extends ArrayAccess<TKey|null, T>
 */
interface Collection extends \Countable, \IteratorAggregate, \ArrayAccess
{
}
