<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PhpParser\Node\Scalar\MagicConst;

use _PhpScopere8e811afab72\PhpParser\Node\Scalar\MagicConst;
class Dir extends \_PhpScopere8e811afab72\PhpParser\Node\Scalar\MagicConst
{
    public function getName() : string
    {
        return '__DIR__';
    }
    public function getType() : string
    {
        return 'Scalar_MagicConst_Dir';
    }
}