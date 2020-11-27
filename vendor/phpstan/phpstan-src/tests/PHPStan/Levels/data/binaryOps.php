<?php

namespace _PhpScoper006a73f0e455\Levels\BinaryOps;

class Foo
{
    /**
     * @param int $int
     * @param string $string
     * @param int|string $intOrString
     * @param string|object $stringOrObject
     */
    public function doFoo(int $int, string $string, $intOrString, $stringOrObject)
    {
        $int + $int;
        $int + $intOrString;
        $int + $stringOrObject;
        $int + $string;
        $string + $string;
        $intOrString + $stringOrObject;
        $intOrString + $string;
        $stringOrObject + $stringOrObject;
    }
}