<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use Rector\DeadCode\Rector\Class_\RemoveUnusedClassesRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\DeadCode\Rector\Class_\RemoveUnusedClassesRector::class);
};
