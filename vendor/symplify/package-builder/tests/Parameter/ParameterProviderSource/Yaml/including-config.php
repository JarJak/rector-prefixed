<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $containerConfigurator->import(__DIR__ . '/included-config.php');
    $parameters = $containerConfigurator->parameters();
    $parameters->set('two', 2);
};