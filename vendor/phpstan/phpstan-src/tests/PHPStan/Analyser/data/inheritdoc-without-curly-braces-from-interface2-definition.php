<?php

namespace _PhpScoper006a73f0e455\InheritDocWithoutCurlyBracesFromInterface2;

interface FooInterface extends \_PhpScoper006a73f0e455\InheritDocWithoutCurlyBracesFromInterface2\BarInterface
{
}
interface BarInterface
{
    /**
     * @param int $int
     */
    public function doBar($int);
}
