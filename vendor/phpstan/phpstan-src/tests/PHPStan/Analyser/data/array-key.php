<?php

namespace _PhpScopera143bcca66cb\ArrayKey;

use function PHPStan\Analyser\assertType;
class Foo
{
    /**
     * @param array-key $arrayKey
     * @param array<array-key, string> $arrayWithArrayKey
     */
    public function doFoo($arrayKey, array $arrayWithArrayKey) : void
    {
        \PHPStan\Analyser\assertType('(int|string)', $arrayKey);
        \PHPStan\Analyser\assertType('array<string>', $arrayWithArrayKey);
    }
}
