<?php

namespace _PhpScoper88fe6e0ad041\StrictComparison71;

class Foo
{
    public function returnsNullableString() : ?bool
    {
        return \false;
    }
    public function doCheckNullableString() : int
    {
        $result = $this->returnsNullableString();
        if ($result === \true) {
            return 1;
        } else {
            if ($result === \false) {
                return 2;
            } else {
                if ($result === null) {
                    return 3;
                }
            }
        }
        return 4;
    }
    public function doCheckNullableAndAddString(?int $memoryLimit) : void
    {
        if ($memoryLimit === null) {
            $memoryLimit = 'abc';
        }
        if ($memoryLimit === 'abc') {
            // doSomething
        }
    }
}
