<?php

namespace _PhpScoperabd03f0baf05\ClassAncestorsImplements;

/**
 * @template T
 * @template U of \Exception
 */
interface FooGeneric
{
}
/**
 * @template T
 * @template V of \Exception
 */
interface FooGeneric2
{
}
/**
 * @template T
 * @template W of \Exception
 */
interface FooGeneric3
{
}
/**
 * @implements FooGeneric<int, \InvalidArgumentException>
 */
class FooDoesNotImplementAnything
{
}
/**
 * @implements FooGeneric<int, \InvalidArgumentException>
 * @implements FooGeneric2<int, \InvalidArgumentException>
 */
class FooInvalidImplementsTags implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric2<int, \InvalidArgumentException>
 */
class FooWrongClassImplemented implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric, \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric3
{
}
/**
 * @implements class-string<T>
 */
class FooWrongTypeInImplementsTag implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int, \InvalidArgumentException>
 */
class FooCorrect implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int>
 */
class FooNotEnough implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int, \InvalidArgumentException, string>
 */
class FooExtraTypes implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int, \Throwable>
 */
class FooNotSubtype implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int, \stdClass>
 */
class FooAlsoNotSubtype implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<Zazzuuuu, \Exception>
 */
class FooUnknownClass implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \Throwable
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric2 implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \Exception
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric3 implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \InvalidArgumentException
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric4 implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T
 * @implements FooGeneric<T, \Exception>
 */
class FooGenericGeneric5 implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \stdClass
 * @implements FooGeneric<T, \Exception>
 */
class FooGenericGeneric6 implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \stdClass
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric7 implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \stdClass
 * @implements FooGeneric<int, T>
 * @implements FooGeneric2<int, T>
 */
class FooGenericGeneric8 implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric, \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric2
{
}
interface NonGenericInterface
{
}
class FooImplementsNonGenericInterface implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\NonGenericInterface
{
}
class FooImplementsGenericInterface implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template-covariant T
 * @template U
 */
interface FooGeneric9
{
}
/**
 * @template-covariant T
 * @implements FooGeneric9<T, T>
 */
class FooGeneric10 implements \_PhpScoperabd03f0baf05\ClassAncestorsImplements\FooGeneric9
{
}
