<?php

namespace _PhpScoperabd03f0baf05\Bug4076;

class Foo
{
    function test(int $x, int $y) : int
    {
        switch ($x) {
            case 0:
                return 0;
            case 1:
                if ($y == 2) {
                    // continue after the switch
                    break;
                }
            default:
                return 99;
        }
        return -1;
    }
}
