<?php

namespace _PhpScoper006a73f0e455\Bug4003;

class Boo
{
    /** @return int */
    public function foo()
    {
        return 1;
    }
}
class Baz extends \_PhpScoper006a73f0e455\Bug4003\Boo
{
    public function foo() : string
    {
        return 'test';
    }
}
class Lorem
{
    public function doFoo(int $test)
    {
    }
}
class Ipsum extends \_PhpScoper006a73f0e455\Bug4003\Lorem
{
    /**
     * @param string $test
     */
    public function doFoo($test)
    {
    }
}
interface Dolor
{
    /**
     * @return void
     * @phpstan-return never
     */
    public function bar();
}
class Amet implements \_PhpScoper006a73f0e455\Bug4003\Dolor
{
    public function bar() : void
    {
        throw new \Exception();
    }
}