<?php

declare (strict_types=1);
namespace RectorPrefix20210118;

use Rector\Polyfill\Rector\If_\UnwrapFutureCompatibleIfFunctionExistsRector;
use RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Polyfill\Rector\If_\UnwrapFutureCompatibleIfFunctionExistsRector::class);
};
