<?php

namespace _PhpScoperabd03f0baf05\CallClosureBind;

class Bar
{
    public function fooMethod() : \_PhpScoperabd03f0baf05\CallClosureBind\Foo
    {
        \Closure::bind(function (\_PhpScoperabd03f0baf05\CallClosureBind\Foo $foo) {
            $foo->privateMethod();
            $foo->nonexistentMethod();
        }, null, \_PhpScoperabd03f0baf05\CallClosureBind\Foo::class);
        $this->fooMethod();
        $this->barMethod();
        $foo = new \_PhpScoperabd03f0baf05\CallClosureBind\Foo();
        $foo->privateMethod();
        $foo->nonexistentMethod();
        \Closure::bind(function () {
            $this->fooMethod();
            $this->barMethod();
        }, $nonexistent, self::class);
        \Closure::bind(function (\_PhpScoperabd03f0baf05\CallClosureBind\Foo $foo) {
            $foo->privateMethod();
            $foo->nonexistentMethod();
        }, null, '_PhpScoperabd03f0baf05\\CallClosureBind\\Foo');
        \Closure::bind(function (\_PhpScoperabd03f0baf05\CallClosureBind\Foo $foo) {
            $foo->privateMethod();
            $foo->nonexistentMethod();
        }, null, new \_PhpScoperabd03f0baf05\CallClosureBind\Foo());
        \Closure::bind(function () {
            // $this is Foo
            $this->privateMethod();
            $this->nonexistentMethod();
        }, $this->fooMethod(), \_PhpScoperabd03f0baf05\CallClosureBind\Foo::class);
        (function () {
            $this->publicMethod();
        })->call(new \_PhpScoperabd03f0baf05\CallClosureBind\Foo());
    }
}
