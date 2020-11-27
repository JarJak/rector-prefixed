<?php

namespace _PhpScoper006a73f0e455\ClassAncestorsImplements;

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
class FooInvalidImplementsTags implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric2<int, \InvalidArgumentException>
 */
class FooWrongClassImplemented implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric, \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric3
{
}
/**
 * @implements class-string<T>
 */
class FooWrongTypeInImplementsTag implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int, \InvalidArgumentException>
 */
class FooCorrect implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int>
 */
class FooNotEnough implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int, \InvalidArgumentException, string>
 */
class FooExtraTypes implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int, \Throwable>
 */
class FooNotSubtype implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<int, \stdClass>
 */
class FooAlsoNotSubtype implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @implements FooGeneric<Zazzuuuu, \Exception>
 */
class FooUnknownClass implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \Throwable
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric2 implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \Exception
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric3 implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \InvalidArgumentException
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric4 implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T
 * @implements FooGeneric<T, \Exception>
 */
class FooGenericGeneric5 implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \stdClass
 * @implements FooGeneric<T, \Exception>
 */
class FooGenericGeneric6 implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \stdClass
 * @implements FooGeneric<int, T>
 */
class FooGenericGeneric7 implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
{
}
/**
 * @template T of \stdClass
 * @implements FooGeneric<int, T>
 * @implements FooGeneric2<int, T>
 */
class FooGenericGeneric8 implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric, \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric2
{
}
interface NonGenericInterface
{
}
class FooImplementsNonGenericInterface implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\NonGenericInterface
{
}
class FooImplementsGenericInterface implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric
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
class FooGeneric10 implements \_PhpScoper006a73f0e455\ClassAncestorsImplements\FooGeneric9
{
}