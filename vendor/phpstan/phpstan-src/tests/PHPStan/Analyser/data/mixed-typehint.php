<?php

namespace _PhpScoperabd03f0baf05\MixedTypehint;

use function PHPStan\Analyser\assertType;
class Foo
{
    public function doFoo(mixed $foo)
    {
        \PHPStan\Analyser\assertType('mixed', $foo);
        \PHPStan\Analyser\assertType('mixed', $this->doBar());
    }
    public function doBar() : mixed
    {
    }
}
function doFoo(mixed $foo)
{
    \PHPStan\Analyser\assertType('mixed', $foo);
}
function (mixed $foo) {
    \PHPStan\Analyser\assertType('mixed', $foo);
    $f = function () : mixed {
    };
    \PHPStan\Analyser\assertType('mixed', $f());
};
