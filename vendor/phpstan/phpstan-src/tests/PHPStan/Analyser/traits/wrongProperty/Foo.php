<?php

namespace _PhpScoperabd03f0baf05\TraitsWrongProperty;

use _PhpScoperabd03f0baf05\Lorem as Bar;
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
