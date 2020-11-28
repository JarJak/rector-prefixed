<?php

namespace _PhpScoperabd03f0baf05\PropertyAttributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Foo
{
}
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Bar
{
}
#[\Attribute(\Attribute::TARGET_ALL)]
class Baz
{
}
class Lorem
{
    #[Foo]
    private $foo;
}
class Ipsum
{
    #[Bar]
    private $foo;
}
class Dolor
{
    #[Baz]
    private $foo;
}
