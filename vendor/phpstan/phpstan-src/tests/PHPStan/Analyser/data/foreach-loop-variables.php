<?php

namespace _PhpScoper006a73f0e455\LoopVariables;

class ForeachFoo
{
    /** @var int[] */
    private $property = [];
    public function doFoo(string $s)
    {
        $foo = null;
        $key = null;
        $val = null;
        $nullableVal = null;
        $this->property = [];
        $integers = [];
        $i = 0;
        $iterableArray = [];
        if (\rand(0, 1) === 0) {
            $iterableArray = [1, 2, 3];
        }
        $falseOrObject = \false;
        foreach ($iterableArray as $key => $val) {
            'begin';
            $foo = new \_PhpScoper006a73f0e455\LoopVariables\Foo();
            'afterAssign';
            if ($nullableVal === null) {
                'nullableValIf';
                $nullableVal = 1;
            } else {
                $nullableVal *= 10;
                'nullableValElse';
            }
            if ($falseOrObject === \false) {
                $falseOrObject = new \_PhpScoper006a73f0e455\LoopVariables\Foo();
            }
            $foo && $i++;
            $nullableInt = $val;
            if (\rand(0, 1) === 1) {
                $nullableInt = null;
            }
            if (something()) {
                $foo = new \_PhpScoper006a73f0e455\LoopVariables\Bar();
                break;
            }
            if (something()) {
                $foo = new \_PhpScoper006a73f0e455\LoopVariables\Baz();
                return;
            }
            if (something()) {
                $foo = new \_PhpScoper006a73f0e455\LoopVariables\Lorem();
                continue;
            }
            if ($nullableInt === null) {
                continue;
            }
            if (isset($this->property[$s])) {
                continue;
            }
            $this->property[$s] = $val;
            $integers[] = $nullableInt;
            'end';
        }
        $emptyForeachKey = null;
        $emptyForeachVal = null;
        foreach ($iterableArray as $emptyForeachKey => $emptyForeachVal) {
        }
        'afterLoop';
    }
}
