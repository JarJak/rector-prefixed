<?php

declare (strict_types=1);
namespace _PhpScopera143bcca66cb;

use Rector\PHPUnit\Rector\MethodCall\UseSpecificWillMethodRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\PHPUnit\Rector\MethodCall\UseSpecificWillMethodRector::class);
};
