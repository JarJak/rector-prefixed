<?php

namespace _PhpScoper006a73f0e455\Levels\Casts;

class Foo
{
    /**
     * @param mixed[] $array
     * @param mixed[]|callable $arrayOrCallable
     * @param mixed[]|float|int $arrayOrFloatOrInt
     */
    public function doFoo(array $array, $arrayOrCallable, $arrayOrFloatOrInt)
    {
        $test = (int) $array;
        $test = (int) $arrayOrCallable;
        $test = (string) $arrayOrFloatOrInt;
    }
}
