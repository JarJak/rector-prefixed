<?php

namespace _PhpScoper006a73f0e455;

if (\class_exists('ReflectionUnionType', \false)) {
    return;
}
class ReflectionUnionType extends \ReflectionType
{
    /** @return ReflectionType[] */
    public function getTypes()
    {
        return [];
    }
}
\class_alias('_PhpScoper006a73f0e455\\ReflectionUnionType', 'ReflectionUnionType', \false);