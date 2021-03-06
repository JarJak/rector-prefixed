<?php

declare (strict_types=1);
namespace Rector\Naming\Contract;

use PhpParser\Node;
interface RenamerInterface
{
    public function rename(\Rector\Naming\Contract\RenameValueObjectInterface $renameValueObject) : ?\PhpParser\Node;
}
