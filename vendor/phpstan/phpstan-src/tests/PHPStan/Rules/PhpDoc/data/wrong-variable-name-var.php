<?php

namespace _PhpScoperabd03f0baf05\WrongVariableNameVarTag;

class Foo
{
    public function doFoo()
    {
        /** @var int $test */
        $test = doFoo();
        /** @var int */
        $test = doFoo();
        /** @var int $foo */
        $test = doFoo();
        /**
         * @var int
         * @var string
         */
        $test = doFoo();
    }
    public function doBar(array $list)
    {
        /** @var int[] $list */
        foreach ($list as $key => $var) {
            // ERROR
        }
        /** @var int $key */
        foreach ($list as $key => $var) {
        }
        /** @var int $var */
        foreach ($list as $key => $var) {
        }
        /**
         * @var int $foo
         * @var int $bar
         * @var int $baz
         * @var int $lorem
         */
        foreach ($list as $key => [$foo, $bar, [$baz, $lorem]]) {
        }
        /**
         * @var int $foo
         * @var int $bar
         * @var int $baz
         * @var int $lorem
         */
        foreach ($list as $key => list($foo, $bar, list($baz, $lorem))) {
        }
        /**
         * @var int $foo
         */
        foreach ($list as $key => $val) {
        }
        /** @var int */
        foreach ($list as $key => $val) {
        }
    }
    public function doBaz()
    {
        /** @var int $var */
        static $var;
        /** @var int */
        static $var;
        /** @var int */
        static $var, $bar;
        /**
         * @var int
         * @var string
         */
        static $var, $bar;
        /** @var int $foo */
        static $test;
    }
    public function doLorem($test)
    {
        /** @var int $test */
        $test2 = doFoo();
        /** @var int */
        $test->foo();
        /** @var int $test */
        $test->foo();
        /** @var int $foo */
        $test->foo();
    }
    public function multiplePrefixedTagsAreFine()
    {
        /**
         * @var int
         * @phpstan-var int
         * @psalm-var int
         */
        $test = doFoo();
        // OK
        /**
         * @var int
         * @var string
         */
        $test = doFoo();
        // error
    }
    public function testEcho($a)
    {
        /** @var string $a */
        echo $a;
        /** @var string $b */
        echo $a;
    }
    public function throwVar($a)
    {
        /** @var \Exception $a */
        throw $a;
    }
    public function throwVar2($a)
    {
        /** @var \Exception */
        throw $a;
    }
    public function throwVar3($a)
    {
        /**
         * @var \Exception
         * @var \InvalidArgumentException
         */
        throw $a;
    }
    public function returnVar($a)
    {
        /** @var \stdClass $a */
        return $a;
    }
    public function returnVar2($a)
    {
        /** @var \stdClass */
        return $a;
    }
    public function returnVar3($a)
    {
        /**
         * @var \stdClass
         * @var \DateTime
         */
        return $a;
    }
    public function thisInVar1()
    {
        /** @var Repository $this */
        $this->demo();
    }
    public function thisInVar2()
    {
        /** @var Repository $this */
        $demo = $this->demo();
    }
}
