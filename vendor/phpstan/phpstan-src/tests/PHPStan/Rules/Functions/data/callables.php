<?php

namespace _PhpScoper006a73f0e455\CallCallables;

class Foo
{
    public function doFoo($mixed, callable $callable, string $string, \Closure $closure)
    {
        $mixed();
        $callable();
        $string();
        $closure();
        $date = 'date';
        $date();
        $date('j. n. Y');
        $nonexistent = 'nonexistent';
        $nonexistent();
    }
    public function doBar(int $i)
    {
        [$this, 'doBar'](1);
        [$this, 'doBar']('string');
    }
    public static function doStaticBaz()
    {
        ['_PhpScoper006a73f0e455\\CallCallables\\Foo', 'doStaticBaz']();
        ['_PhpScoper006a73f0e455\\CallCallables\\Foo', 'doStaticBaz']('foo');
        ('CallCallables\\Foo::doStaticBaz')();
        ('CallCallables\\Foo::doStaticBaz')('foo');
    }
    private function privateFooMethod()
    {
    }
}
function (\Closure $closure) {
    [new \_PhpScoper006a73f0e455\CallCallables\Foo(), 'privateFooMethod']();
    $closure(1, 2, 3);
    $literalClosure = function (int $i, int $j = 1) : void {
    };
    $literalClosure();
    $result = $literalClosure(1);
    $variadicClosure = function (int $i, int ...$j) {
    };
    $variadicClosure();
};
function () {
    $f = function (int $i) use(&$f) {
        $f(1);
        $f('foo');
    };
};
function () {
    $foo = new class
    {
        public function __invoke(string $str)
        {
        }
    };
    $foo(1);
};
function () {
    $emptyString = '';
    $emptyString(1, 2, 3);
};
function (\_PhpScoper006a73f0e455\CallCallables\Bar $bar) {
    $bar();
};
class Baz
{
    /**
     * @param Foo[] $foos
     */
    public function doFoo(array $foos)
    {
        $f = function (\_PhpScoper006a73f0e455\CallCallables\Foo ...$foo) {
        };
        $f($foos);
        $f(...$foos);
    }
    public function doBar()
    {
        $baz = new \_PhpScoper006a73f0e455\CallCallables\Baz();
        $baz();
        if (\method_exists($baz, '__invoke')) {
            $baz();
        }
    }
}
class MethodExistsCheckFirst
{
    public function doFoo(object $object)
    {
        if (\method_exists($object, 'foo')) {
            [$object, 'foo']();
            [$object, 'bar']();
        }
    }
}
class RequiredArgumentsAfterDefaultValues
{
    public function doFoo()
    {
        $c = function ($a, $b = 'b', $c) {
        };
        $c();
        $c('a');
        $c('a', 'b');
        $c('a', 'b', 'c');
    }
}
class ObjectCallable
{
    /**
     * @param object $object
     */
    public function doFoo($object)
    {
        $cb = [$object, 'yo'];
        $cb();
        if (\is_callable($cb)) {
            $cb();
        } else {
            $cb();
        }
    }
}
class CallableInForeach
{
    public function doFoo(bool $foo = \true) : void
    {
        $callback = [self::class, $foo ? 'foo' : 'bar'];
        $callback();
        \assert(\is_callable($callback));
        $callback();
        foreach ([0, 1] as $i) {
            echo $callback();
        }
    }
}
