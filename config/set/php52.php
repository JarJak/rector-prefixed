<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa;

use _PhpScoper0a2ac50786fa\Rector\Generic\Rector\FuncCall\RemoveFuncCallArgRector;
use _PhpScoper0a2ac50786fa\Rector\Generic\ValueObject\RemoveFuncCallArg;
use _PhpScoper0a2ac50786fa\Rector\Php52\Rector\Property\VarToPublicPropertyRector;
use _PhpScoper0a2ac50786fa\Rector\Php52\Rector\Switch_\ContinueToBreakInSwitchRector;
use _PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\_PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Php52\Rector\Property\VarToPublicPropertyRector::class);
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Php52\Rector\Switch_\ContinueToBreakInSwitchRector::class);
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Generic\Rector\FuncCall\RemoveFuncCallArgRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\Generic\Rector\FuncCall\RemoveFuncCallArgRector::REMOVED_FUNCTION_ARGUMENTS => \_PhpScoper0a2ac50786fa\Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([
        // see https://www.php.net/manual/en/function.ldap-first-attribute.php
        new \_PhpScoper0a2ac50786fa\Rector\Generic\ValueObject\RemoveFuncCallArg('ldap_first_attribute', 2),
    ])]]);
};
