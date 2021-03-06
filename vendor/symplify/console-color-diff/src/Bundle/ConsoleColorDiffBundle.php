<?php

declare (strict_types=1);
namespace RectorPrefix20210118\Symplify\ConsoleColorDiff\Bundle;

use RectorPrefix20210118\Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use RectorPrefix20210118\Symfony\Component\HttpKernel\Bundle\Bundle;
use RectorPrefix20210118\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension;
final class ConsoleColorDiffBundle extends \RectorPrefix20210118\Symfony\Component\HttpKernel\Bundle\Bundle
{
    protected function createContainerExtension() : ?\RectorPrefix20210118\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new \RectorPrefix20210118\Symplify\ConsoleColorDiff\DependencyInjection\Extension\ConsoleColorDiffExtension();
    }
}
