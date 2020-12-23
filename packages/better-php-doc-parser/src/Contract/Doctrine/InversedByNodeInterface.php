<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\Doctrine;

interface InversedByNodeInterface
{
    public function getInversedBy() : ?string;
    public function removeInversedBy() : void;
}
