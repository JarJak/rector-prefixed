<?php

namespace _PhpScopera143bcca66cb\TraitsWrongProperty;

use _PhpScopera143bcca66cb\Lorem as Bar;
class Foo
{
    use FooTrait;
    public function doFoo() : void
    {
        $this->id = 1;
        $this->id = 'foo';
        $this->bar = 1;
    }
}
