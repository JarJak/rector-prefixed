<?php

namespace _PhpScoper006a73f0e455\Bug3915;

use function PHPStan\Analyser\assertType;
class HelloWorld
{
    public function sayHello() : void
    {
        $lengths = [0];
        foreach ([1] as $row) {
            $lengths[] = self::getInt();
        }
        \PHPStan\Analyser\assertType('array<int, int>&nonEmpty', $lengths);
    }
    public static function getInt() : int
    {
        return 5;
    }
}