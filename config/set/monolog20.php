<?php

declare (strict_types=1);
namespace _PhpScoper567b66d83109;

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use _PhpScoper567b66d83109\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
# https://github.com/Seldaek/monolog/commit/39f8a20e6dadc0194e846b254c5f23d1c732290b#diff-dce565f403e044caa5e6a0d988339430
return static function (\_PhpScoper567b66d83109\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'addDebug', 'debug'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'addInfo', 'info'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'addNotice', 'notice'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'addWarning', 'warning'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'addError', 'error'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'addCritical', 'critical'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'addAlert', 'alert'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'addEmergency', 'emergency'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'warn', 'warning'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'err', 'error'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'crit', 'critical'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper567b66d83109\\Monolog\\Logger', 'emerg', 'emergency')])]]);
};
