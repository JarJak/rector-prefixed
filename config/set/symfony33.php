<?php

declare (strict_types=1);
namespace RectorPrefix20201226;

use Rector\Generic\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Generic\ValueObject\ArgumentAdder;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Symfony3\Rector\ClassConstFetch\ConsoleExceptionToErrorEventConstantRector;
use RectorPrefix20201226\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\RectorPrefix20201226\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Generic\Rector\ClassMethod\ArgumentAdderRector::class)->call('configure', [[\Rector\Generic\Rector\ClassMethod\ArgumentAdderRector::ADDED_ARGUMENTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\ArgumentAdder('RectorPrefix20201226\\Symfony\\Component\\DependencyInjection\\ContainerBuilder', 'compile', 2, '__unknown__', 0), new \Rector\Generic\ValueObject\ArgumentAdder('RectorPrefix20201226\\Symfony\\Component\\DependencyInjection\\ContainerBuilder', 'addCompilerPass', 2, 'priority', 0), new \Rector\Generic\ValueObject\ArgumentAdder('RectorPrefix20201226\\Symfony\\Component\\DependencyInjection\\Compiler\\ServiceReferenceGraph', 'connect', 6, 'weak', \false)])]]);
    $services->set(\Rector\Symfony3\Rector\ClassConstFetch\ConsoleExceptionToErrorEventConstantRector::class);
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => [
        # console
        'RectorPrefix20201226\\Symfony\\Component\\Console\\Event\\ConsoleExceptionEvent' => 'RectorPrefix20201226\\Symfony\\Component\\Console\\Event\\ConsoleErrorEvent',
        # debug
        'RectorPrefix20201226\\Symfony\\Component\\Debug\\Exception\\ContextErrorException' => 'ErrorException',
        # dependency-injection
        'RectorPrefix20201226\\Symfony\\Component\\DependencyInjection\\DefinitionDecorator' => 'RectorPrefix20201226\\Symfony\\Component\\DependencyInjection\\ChildDefinition',
        # framework bundle
        'RectorPrefix20201226\\Symfony\\Bundle\\FrameworkBundle\\DependencyInjection\\Compiler\\AddConsoleCommandPass' => 'RectorPrefix20201226\\Symfony\\Component\\Console\\DependencyInjection\\AddConsoleCommandPass',
        'RectorPrefix20201226\\Symfony\\Bundle\\FrameworkBundle\\DependencyInjection\\Compiler\\SerializerPass' => 'RectorPrefix20201226\\Symfony\\Component\\Serializer\\DependencyInjection\\SerializerPass',
        'RectorPrefix20201226\\Symfony\\Bundle\\FrameworkBundle\\DependencyInjection\\Compiler\\FormPass' => 'RectorPrefix20201226\\Symfony\\Component\\Form\\DependencyInjection\\FormPass',
        'RectorPrefix20201226\\Symfony\\Bundle\\FrameworkBundle\\EventListener\\SessionListener' => 'RectorPrefix20201226\\Symfony\\Component\\HttpKernel\\EventListener\\SessionListener',
        'RectorPrefix20201226\\Symfony\\Bundle\\FrameworkBundle\\EventListener\\TestSessionListenr' => 'RectorPrefix20201226\\Symfony\\Component\\HttpKernel\\EventListener\\TestSessionListener',
        'RectorPrefix20201226\\Symfony\\Bundle\\FrameworkBundle\\DependencyInjection\\Compiler\\ConfigCachePass' => 'RectorPrefix20201226\\Symfony\\Component\\Config\\DependencyInjection\\ConfigCachePass',
        'RectorPrefix20201226\\Symfony\\Bundle\\FrameworkBundle\\DependencyInjection\\Compiler\\PropertyInfoPass' => 'RectorPrefix20201226\\Symfony\\Component\\PropertyInfo\\DependencyInjection\\PropertyInfoPass',
    ]]]);
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('RectorPrefix20201226\\Symfony\\Component\\DependencyInjection\\Container', 'isFrozen', 'isCompiled')])]]);
};
