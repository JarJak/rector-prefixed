<?php

declare (strict_types=1);
namespace RectorPrefix20210106;

use Rector\DeadCode\Rector\Class_\RemoveUnusedClassesRector;
use RectorPrefix20210106\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210106\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\DeadCode\Rector\Class_\RemoveUnusedClassesRector::class);
};
