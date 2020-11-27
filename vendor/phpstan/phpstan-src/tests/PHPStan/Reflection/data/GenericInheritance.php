<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\GenericInheritance;

/**
 * interface I0
 *
 * @template T
 */
interface I0
{
}
/**
 * interface I1
 *
 * @template T
 */
interface I1
{
}
/**
 * interface I
 *
 * @template T
 *
 * @extends I0<T>
 * @extends I1<int>
 */
interface I extends \_PhpScoper006a73f0e455\GenericInheritance\I0, \_PhpScoper006a73f0e455\GenericInheritance\I1
{
}
/**
 * class C0
 *
 * @template T
 *
 * @implements I<T>
 */
class C0 implements \_PhpScoper006a73f0e455\GenericInheritance\I
{
}
/**
 * class C
 *
 * @extends C0<\DateTime>
 */
class C extends \_PhpScoper006a73f0e455\GenericInheritance\C0
{
}
/**
 * @implements I<\DateTimeInterface>
 */
class Override extends \_PhpScoper006a73f0e455\GenericInheritance\C
{
}