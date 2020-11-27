<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Rector\\NodeNestingScope\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/ValueObject']);
};
