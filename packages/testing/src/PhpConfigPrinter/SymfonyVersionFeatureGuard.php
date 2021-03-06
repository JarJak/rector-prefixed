<?php

declare (strict_types=1);
namespace Rector\Testing\PhpConfigPrinter;

use RectorPrefix20210118\Symplify\PhpConfigPrinter\Contract\SymfonyVersionFeatureGuardInterface;
final class SymfonyVersionFeatureGuard implements \RectorPrefix20210118\Symplify\PhpConfigPrinter\Contract\SymfonyVersionFeatureGuardInterface
{
    public function isAtLeastSymfonyVersion(float $symfonyVersion) : bool
    {
        return \true;
    }
}
