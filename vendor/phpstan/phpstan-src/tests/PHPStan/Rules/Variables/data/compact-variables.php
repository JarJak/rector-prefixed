<?php

namespace _PhpScoper26e51eeacccf\CompactVariables;

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
