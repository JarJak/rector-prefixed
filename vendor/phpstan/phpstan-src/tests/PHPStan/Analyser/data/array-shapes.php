<?php

namespace _PhpScoperabd03f0baf05\ArrayShapesInPhpDoc;

class Foo
{
    /**
     * @param array{0: string, 1: Foo, foo:Bar, Baz} $one
     * @param array{0: string, 1?: Foo, foo?:Bar} $two
     * @param array{0?: string, 1?: Foo, foo?:Bar} $three
     */
    public function doFoo(array $one, array $two, array $three)
    {
        die;
    }
}
