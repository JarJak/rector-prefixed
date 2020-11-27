<?php

// lint >= 8.0
namespace _PhpScoper006a73f0e455\NativeMixedType;

use function PHPStan\Analyser\assertType;
class Foo
{
    public mixed $fooProp;
    public function doFoo(mixed $foo) : mixed
    {
        \PHPStan\Analyser\assertType('mixed', $foo);
        \PHPStan\Analyser\assertType('mixed', $this->fooProp);
    }
}
class Bar
{
}
function doFoo(mixed $foo) : mixed
{
    \PHPStan\Analyser\assertType('mixed', $foo);
}
function (\_PhpScoper006a73f0e455\NativeMixedType\Foo $foo) : void {
    \PHPStan\Analyser\assertType('mixed', $foo->fooProp);
    \PHPStan\Analyser\assertType('mixed', $foo->doFoo(1));
    \PHPStan\Analyser\assertType('mixed', doFoo(1));
};
function () : void {
    $f = function (mixed $foo) : mixed {
        \PHPStan\Analyser\assertType('mixed', $foo);
    };
    \PHPStan\Analyser\assertType('mixed', $f(1));
};
