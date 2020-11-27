<?php

declare (strict_types=1);
namespace _PhpScopera143bcca66cb;

use Rector\DowngradePhp71\Rector\ClassConst\DowngradeClassConstantVisibilityRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeNullableTypeParamDeclarationRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeNullableTypeReturnDeclarationRector;
use Rector\DowngradePhp71\Rector\FunctionLike\DowngradeVoidTypeReturnDeclarationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\DowngradePhp71\Rector\FunctionLike\DowngradeNullableTypeParamDeclarationRector::class);
    $services->set(\Rector\DowngradePhp71\Rector\FunctionLike\DowngradeNullableTypeReturnDeclarationRector::class);
    $services->set(\Rector\DowngradePhp71\Rector\FunctionLike\DowngradeVoidTypeReturnDeclarationRector::class);
    $services->set(\Rector\DowngradePhp71\Rector\ClassConst\DowngradeClassConstantVisibilityRector::class);
};
