<?php

namespace _PhpScoper006a73f0e455\IgnoreLine;

class Foo
{
    public function doFoo() : void
    {
        fail();
        // reported
        fail();
        // @phpstan-ignore-line
        fail();
        /* @phpstan-ignore-line */
        fail();
        /** @phpstan-ignore-line */
        fail();
        /** @phpstan-ignore-line */
        fail();
        // reported
        if (fail()) {
            // @phpstan-ignore-line
            fail();
            // reported
        }
        succ();
        /** @phpstan-ignore-line reported as unmatched */
    }
}
