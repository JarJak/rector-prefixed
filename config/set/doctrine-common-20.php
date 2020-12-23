<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa;

use _PhpScoper0a2ac50786fa\Rector\Renaming\Rector\Name\RenameClassRector;
use _PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
# see https://github.com/doctrine/persistence/pull/71
return static function (\_PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Event\\LifecycleEventArgs' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Event\\LifecycleEventArgs', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Event\\LoadClassMetadataEventArgs' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Event\\LoadClassMetadataEventArgs', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Event\\ManagerEventArgs' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Event\\ManagerEventArgs', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\AbstractClassMetadataFactory' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\AbstractClassMetadataFactory', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\ClassMetadata' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\ClassMetadata', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\ClassMetadataFactory' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\ClassMetadataFactory', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\FileDriver' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\Driver\\FileDriver', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\MappingDriver' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\Driver\\MappingDriver', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\MappingDriverChain' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\Driver\\MappingDriverChain', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\PHPDriver' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\Driver\\PHPDriver', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\StaticPHPDriver' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\Driver\\StaticPHPDriver', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\SymfonyFileLocator' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\Driver\\SymfonyFileLocator', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\MappingException' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\MappingException', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\ReflectionService' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\ReflectionService', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\RuntimeReflectionService' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\RuntimeReflectionService', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\StaticReflectionService' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\StaticReflectionService', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\ObjectManager' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\ObjectManager', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\ObjectManagerDecorator' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\ObjectManagerDecorator', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\ObjectRepository' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\ObjectRepository', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Proxy' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Proxy', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\AbstractManagerRegistry' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\AbstractManagerRegistry', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\Mapping\\Driver\\DefaultFileLocator' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\Mapping\\Driver\\DefaultFileLocator', '_PhpScoper0a2ac50786fa\\Doctrine\\Common\\Persistence\\ManagerRegistry' => '_PhpScoper0a2ac50786fa\\Doctrine\\Persistence\\ManagerRegistry']]]);
};
