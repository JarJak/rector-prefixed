<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Symfony4\Tests\Rector\MethodCall\ContainerGetToConstructorInjectionRector\Source;

use _PhpScopere8e811afab72\Symfony\Component\Console\Command\Command;
use _PhpScopere8e811afab72\Symfony\Component\DependencyInjection\ContainerInterface;
class ContainerAwareParentCommand extends \_PhpScopere8e811afab72\Symfony\Component\Console\Command\Command
{
    public function getContainer() : \_PhpScopere8e811afab72\Symfony\Component\DependencyInjection\ContainerInterface
    {
    }
}