<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa;

use _PhpScoper0a2ac50786fa\Rector\PSR4\Composer\PSR4NamespaceMatcher;
use _PhpScoper0a2ac50786fa\Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface;
use _PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\_PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Rector\\PSR4\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Rector']);
    $services->alias(\_PhpScoper0a2ac50786fa\Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface::class, \_PhpScoper0a2ac50786fa\Rector\PSR4\Composer\PSR4NamespaceMatcher::class);
};
