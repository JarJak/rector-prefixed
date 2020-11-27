<?php

namespace _PhpScoper26e51eeacccf\ArrayTypehintWithoutNullInPhpDoc;

use function PHPStan\Analyser\assertType;
class Foo
{
    /**
     * @return string[]
     */
    public function doFoo() : ?array
    {
        return ['foo'];
    }
    public function doBar() : void
    {
        \PHPStan\Analyser\assertType('array<string>|null', $this->doFoo());
    }
    /**
     * @param string[] $a
     */
    public function doBaz(?array $a) : void
    {
        \PHPStan\Analyser\assertType('array<string>|null', $a);
    }
}
