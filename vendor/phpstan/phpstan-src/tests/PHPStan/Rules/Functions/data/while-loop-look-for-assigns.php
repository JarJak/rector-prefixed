<?php

namespace _PhpScoper26e51eeacccf\WhileLoopLookForAssignsInBranchesVariableExistence;

class Foo
{
    public function doFoo()
    {
        $lastOffset = 1;
        while (\false !== ($index = \strpos('abc', \DIRECTORY_SEPARATOR, $lastOffset))) {
            $lastOffset = $index + 1;
        }
    }
}
