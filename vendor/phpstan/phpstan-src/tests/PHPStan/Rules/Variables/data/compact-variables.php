<?php

namespace _PhpScoper006a73f0e455\CompactVariables;

class Foo
{
    /**
     * @return string[]
     */
    public function doFoo(string $foo) : array
    {
        $methodFoo = 'foo';
        $methodBar = 'bar';
        if ($foo === 'defined') {
            $baz = 'maybe defined';
        }
        return \compact($foo, $methodFoo, $methodBar, 'baz');
    }
}
