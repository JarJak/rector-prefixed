<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use Rector\Doctrine\Rector\Class_\AlwaysInitializeUuidInEntityRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Doctrine\Rector\Class_\AlwaysInitializeUuidInEntityRector::class);
};