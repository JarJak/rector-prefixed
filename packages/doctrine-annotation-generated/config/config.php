<?php

declare (strict_types=1);
namespace _PhpScopera143bcca66cb;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Rector\\DoctrineAnnotationGenerated\\', __DIR__ . '/../src');
};
