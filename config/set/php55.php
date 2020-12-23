<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa;

use _PhpScoper0a2ac50786fa\Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use _PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\_PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Php55\Rector\String_\StringClassNameToClassConstantRector::class);
};
