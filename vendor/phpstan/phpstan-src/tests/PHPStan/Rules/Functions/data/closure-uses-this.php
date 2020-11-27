<?php

namespace _PhpScoper26e51eeacccf\ClosureUsesThis;

class Foo
{
    public function doFoo()
    {
        $f = function () {
            // ok
        };
        $that = $this;
        $f = function () use($that) {
            // report
        };
        $f = static function () use($that) {
            // ok
        };
    }
}
