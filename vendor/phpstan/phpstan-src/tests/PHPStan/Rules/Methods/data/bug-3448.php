<?php

namespace _PhpScoper26e51eeacccf\Bug3448;

use function func_get_args;
final class Foo
{
    public static function add(int $lall) : void
    {
        $args = \func_get_args();
    }
}
final class UseFoo
{
    public static function do() : void
    {
        \_PhpScoper26e51eeacccf\Bug3448\Foo::add(1, [new \stdClass()]);
        \_PhpScoper26e51eeacccf\Bug3448\Foo::add('foo');
        \_PhpScoper26e51eeacccf\Bug3448\Foo::add('foo', [new \stdClass()]);
    }
}
