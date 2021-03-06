<?php

declare (strict_types=1);
namespace RectorPrefix20210118;

use Rector\DoctrineGedmoToKnplabs\Rector\Class_\BlameableBehaviorRector;
use Rector\DoctrineGedmoToKnplabs\Rector\Class_\LoggableBehaviorRector;
use Rector\DoctrineGedmoToKnplabs\Rector\Class_\SluggableBehaviorRector;
use Rector\DoctrineGedmoToKnplabs\Rector\Class_\SoftDeletableBehaviorRector;
use Rector\DoctrineGedmoToKnplabs\Rector\Class_\TimestampableBehaviorRector;
use Rector\DoctrineGedmoToKnplabs\Rector\Class_\TranslationBehaviorRector;
use Rector\DoctrineGedmoToKnplabs\Rector\Class_\TreeBehaviorRector;
use RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
# version gedmo/doctrine-extensions 2.x to knplabs/doctrine-behaviors 2.0
return static function (\RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\DoctrineGedmoToKnplabs\Rector\Class_\TimestampableBehaviorRector::class);
    $services->set(\Rector\DoctrineGedmoToKnplabs\Rector\Class_\SluggableBehaviorRector::class);
    $services->set(\Rector\DoctrineGedmoToKnplabs\Rector\Class_\TreeBehaviorRector::class);
    $services->set(\Rector\DoctrineGedmoToKnplabs\Rector\Class_\TranslationBehaviorRector::class);
    $services->set(\Rector\DoctrineGedmoToKnplabs\Rector\Class_\SoftDeletableBehaviorRector::class);
    $services->set(\Rector\DoctrineGedmoToKnplabs\Rector\Class_\BlameableBehaviorRector::class);
    $services->set(\Rector\DoctrineGedmoToKnplabs\Rector\Class_\LoggableBehaviorRector::class);
};
