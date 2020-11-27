<?php

namespace _PhpScoper006a73f0e455\TypesNamespaceCasts;

class Bar
{
    /** @var self */
    private $barProperty;
}
class Foo extends \_PhpScoper006a73f0e455\TypesNamespaceCasts\Bar
{
    /** @var self */
    private $foo;
    /** @var int */
    private $int;
    /** @var int */
    protected $protectedInt;
    /** @var int */
    public $publicInt;
    /**
     * @param string $str
     * @param iterable<string, \DateTimeImmutable> $iterable
     */
    public function doFoo(string $str, iterable $iterable)
    {
        $castedInteger = (int) foo();
        $castedBoolean = (bool) foo();
        $castedFloat = (float) foo();
        $castedString = (string) foo();
        $castedArray = (array) foo();
        $castedObject = (object) foo();
        $foo = new self();
        $castedFoo = (object) $foo;
        /** @var self|array $arrayOrObject */
        $arrayOrObject = foo();
        $castedArrayOrObject = (object) $arrayOrObject;
        /** @var bool $bool */
        $bool = doFoo();
        die;
    }
}