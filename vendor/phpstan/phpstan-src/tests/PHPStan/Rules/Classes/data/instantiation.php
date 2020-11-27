<?php

// lint < 8.0
namespace _PhpScoper26e51eeacccf\TestInstantiation;

class InstantiatingClass
{
    public function __construct(int $i)
    {
    }
    public function doFoo()
    {
        new self();
        new self(1);
        new static();
        // not checked
        new parent();
    }
}
function () {
    new \_PhpScoper26e51eeacccf\TestInstantiation\FooInstantiation();
    new \_PhpScoper26e51eeacccf\TestInstantiation\FooInstantiation();
    new \_PhpScoper26e51eeacccf\TestInstantiation\FooInstantiation(1);
    // additional parameter
    new \_PhpScoper26e51eeacccf\TestInstantiation\FooBarInstantiation();
    // nonexistent
    new \_PhpScoper26e51eeacccf\TestInstantiation\BarInstantiation();
    // missing parameter
    new \_PhpScoper26e51eeacccf\TestInstantiation\LoremInstantiation();
    // abstract
    new \_PhpScoper26e51eeacccf\TestInstantiation\IpsumInstantiation();
    // interface
    $test = 'Test';
    new $test();
    new \_PhpScoper26e51eeacccf\TestInstantiation\ClassWithVariadicConstructor(1, 2, 3);
    new \DatePeriod();
    new \DatePeriod(new \DateTime(), new \DateInterval('P1D'), new \DateTime(), \DatePeriod::EXCLUDE_START_DATE);
    new self();
    new static();
    new parent();
    $a = new \_PhpScoper26e51eeacccf\TestInstantiation\BarInstantiation(1);
    new $a();
};
class ChildInstantiatingClass extends \_PhpScoper26e51eeacccf\TestInstantiation\InstantiatingClass
{
    public function __construct(int $i, int $j)
    {
        parent::__construct($i);
    }
    public function doBar()
    {
        new parent();
        new parent(1);
    }
}
function () {
    new \_PhpScoper26e51eeacccf\TestInstantiation\FOOInstantiation(1, 2, 3);
    new \_PhpScoper26e51eeacccf\TestInstantiation\BARInstantiation();
    new \_PhpScoper26e51eeacccf\TestInstantiation\BARInstantiation(1);
};
class PrivateConstructorClass
{
    private function __construct(int $i)
    {
    }
}
class ProtectedConstructorClass
{
    protected function __construct(int $i)
    {
    }
}
class ClassExtendsProtectedConstructorClass extends \_PhpScoper26e51eeacccf\TestInstantiation\ProtectedConstructorClass
{
    public function doFoo()
    {
        new self();
    }
}
class ExtendsPrivateConstructorClass extends \_PhpScoper26e51eeacccf\TestInstantiation\PrivateConstructorClass
{
    public function doFoo()
    {
        new self();
    }
}
function () {
    new \_PhpScoper26e51eeacccf\TestInstantiation\PrivateConstructorClass(1);
    new \_PhpScoper26e51eeacccf\TestInstantiation\ProtectedConstructorClass(1);
    new \_PhpScoper26e51eeacccf\TestInstantiation\ClassExtendsProtectedConstructorClass(1);
    new \_PhpScoper26e51eeacccf\TestInstantiation\ExtendsPrivateConstructorClass(1);
};
function () {
    new \Exception(123, 'code');
};
class NoConstructor
{
}
function () {
    new \_PhpScoper26e51eeacccf\TestInstantiation\NoConstructor();
    new \_PhpScoper26e51eeacccf\TestInstantiation\NOCONSTRUCTOR();
};
function () {
    new class(1)
    {
        public function __construct($i)
        {
        }
    };
    new class(1, 2, 3)
    {
        public function __construct($i)
        {
        }
    };
};
class DoWhileVariableReassignment
{
    public function doFoo()
    {
        $arr = [];
        do {
            $arr = new self($arr);
        } while ($arr = [1]);
    }
    public function __construct(array $arr)
    {
    }
}
class ClassInExpression
{
    public static function doFoo(string $key) : void
    {
        $a = 'UndefinedClass1';
        new $a();
        $b = ['UndefinedClass2'];
        new $b[0]();
        $classes = ['key1' => self::class, 'key2' => 'UndefinedClass3'];
        new $classes[$key]();
    }
}
final class FinalClass
{
    public function doFoo()
    {
        new static();
        new static(1);
    }
}
class ClassWithFinalConstructor
{
    public final function __construct(int $i)
    {
    }
    public function doFoo()
    {
        new static(1);
        new static();
    }
}
interface InterfaceWithConstructor
{
    public function __construct(int $i);
}
class ConstructorComingFromAnInterface implements \_PhpScoper26e51eeacccf\TestInstantiation\InterfaceWithConstructor
{
    public function __construct(int $i)
    {
    }
    public function doFoo()
    {
        new static(1);
        new static();
    }
}
abstract class AbstractClassWithFinalConstructor
{
    protected final function __construct()
    {
    }
    public function getInstance()
    {
        new static();
        new static(1);
    }
}
abstract class AbstractConstructor
{
    public abstract function __construct(string $s);
    public function doFoo()
    {
        new static('foo');
        new static();
    }
}
class ClassExtendingAbstractConstructor extends \_PhpScoper26e51eeacccf\TestInstantiation\AbstractConstructor
{
    public function __construct(string $s)
    {
    }
    public function doBar()
    {
        new static('foo');
        new static();
    }
}
