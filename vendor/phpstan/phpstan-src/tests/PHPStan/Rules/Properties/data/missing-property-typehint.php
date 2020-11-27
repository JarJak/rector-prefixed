<?php

namespace _PhpScoper006a73f0e455\MissingPropertyTypehint;

class MyClass
{
    private $prop1;
    protected $prop2 = null;
    /**
     * @var
     */
    public $prop3;
}
class ChildClass extends \_PhpScoper006a73f0e455\MissingPropertyTypehint\MyClass
{
    /**
     * @var int
     */
    protected $prop1;
    /**
     * @var null
     */
    protected $prop2;
    /**
     * @var \stdClass|array|int|null
     */
    private $unionProp;
}
class PrefixedTags
{
    /** @phpstan-var int */
    private $fooPhpstan;
    /** @psalm-var int */
    private $fooPsalm;
}
/**
 * @template T
 * @template U
 */
interface GenericInterface
{
}
class NonGenericClass
{
}
/**
 * @template A
 * @template B
 */
class GenericClass
{
}
class Bar
{
    /** @var \MissingPropertyTypehint\GenericInterface */
    private $foo;
    /** @var \MissingPropertyTypehint\NonGenericClass */
    private $bar;
    /** @var \MissingPropertyTypehint\GenericClass */
    private $baz;
}
$foo = new class
{
    /** @var float */
    private $dateTime;
};