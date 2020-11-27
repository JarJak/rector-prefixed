<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\InheritDocMergingTemplate;

use function PHPStan\Analyser\assertType;
class Foo
{
    /**
     * @template T
     * @template U
     * @param T $a
     * @param U $b
     * @return T|array<U>
     */
    public function doFoo($a, $b)
    {
    }
}
class Bar extends \_PhpScoper26e51eeacccf\InheritDocMergingTemplate\Foo
{
    public function doFoo($a, $b)
    {
        \PHPStan\Analyser\assertType('T (method InheritDocMergingTemplate\\Foo::doFoo(), argument)', $a);
        \PHPStan\Analyser\assertType('U (method InheritDocMergingTemplate\\Foo::doFoo(), argument)', $b);
    }
    public function doBar()
    {
        \PHPStan\Analyser\assertType('array<string>|int', $this->doFoo(1, 'hahaha'));
    }
}
class Dolor extends \_PhpScoper26e51eeacccf\InheritDocMergingTemplate\Foo
{
    /**
     * @param T $a
     * @param U $b
     * @return T|array<U>
     */
    public function doFoo($a, $b)
    {
        \PHPStan\Analyser\assertType('_PhpScoper26e51eeacccf\\InheritDocMergingTemplate\\T', $a);
        \PHPStan\Analyser\assertType('_PhpScoper26e51eeacccf\\InheritDocMergingTemplate\\U', $b);
    }
    public function doBar()
    {
        \PHPStan\Analyser\assertType('_PhpScoper26e51eeacccf\\array<InheritDocMergingTemplate\\U>|InheritDocMergingTemplate\\T', $this->doFoo(1, 'hahaha'));
    }
}
class Sit extends \_PhpScoper26e51eeacccf\InheritDocMergingTemplate\Foo
{
    /**
     * @template T
     * @param T $a
     * @param U $b
     * @return T|array<U>
     */
    public function doFoo($a, $b)
    {
        \PHPStan\Analyser\assertType('T (method InheritDocMergingTemplate\\Sit::doFoo(), argument)', $a);
        \PHPStan\Analyser\assertType('_PhpScoper26e51eeacccf\\InheritDocMergingTemplate\\U', $b);
    }
    public function doBar()
    {
        \PHPStan\Analyser\assertType('array<InheritDocMergingTemplate\\U>|int', $this->doFoo(1, 'hahaha'));
    }
}
class Amet extends \_PhpScoper26e51eeacccf\InheritDocMergingTemplate\Foo
{
    /** SomeComment */
    public function doFoo($a, $b)
    {
        \PHPStan\Analyser\assertType('T (method InheritDocMergingTemplate\\Foo::doFoo(), argument)', $a);
        \PHPStan\Analyser\assertType('U (method InheritDocMergingTemplate\\Foo::doFoo(), argument)', $b);
    }
    public function doBar()
    {
        \PHPStan\Analyser\assertType('array<string>|int', $this->doFoo(1, 'hahaha'));
    }
}
/**
 * @template T of object
 */
class Baz
{
    /**
     * @param T $a
     */
    public function doFoo($a)
    {
    }
}
class Lorem extends \_PhpScoper26e51eeacccf\InheritDocMergingTemplate\Baz
{
    public function doFoo($a)
    {
        \PHPStan\Analyser\assertType('object', $a);
    }
}
/**
 * @extends Baz<\stdClass>
 */
class Ipsum extends \_PhpScoper26e51eeacccf\InheritDocMergingTemplate\Baz
{
    public function doFoo($a)
    {
        \PHPStan\Analyser\assertType('stdClass', $a);
    }
}
