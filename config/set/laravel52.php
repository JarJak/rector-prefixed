<?php

declare (strict_types=1);
namespace RectorPrefix20210117;

use Rector\Generic\Rector\String_\StringToClassConstantRector;
use Rector\Generic\ValueObject\StringToClassConstant;
use Rector\Renaming\Rector\Name\RenameClassRector;
use RectorPrefix20210117\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
# see: https://laravel.com/docs/5.2/upgrade
return static function (\RectorPrefix20210117\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['Illuminate\\Auth\\Access\\UnauthorizedException' => 'Illuminate\\Auth\\Access\\AuthorizationException', 'Illuminate\\Http\\Exception\\HttpResponseException' => 'Illuminate\\Foundation\\Validation\\ValidationException', 'Illuminate\\Foundation\\Composer' => 'Illuminate\\Support\\Composer']]]);
    $services->set(\Rector\Generic\Rector\String_\StringToClassConstantRector::class)->call('configure', [[\Rector\Generic\Rector\String_\StringToClassConstantRector::STRINGS_TO_CLASS_CONSTANTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\StringToClassConstant('artisan.start', 'Illuminate\\Console\\Events\\ArtisanStarting', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('auth.attempting', 'Illuminate\\Auth\\Events\\Attempting', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('auth.login', 'Illuminate\\Auth\\Events\\Login', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('auth.logout', 'Illuminate\\Auth\\Events\\Logout', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.missed', 'Illuminate\\Cache\\Events\\CacheMissed', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.hit', 'Illuminate\\Cache\\Events\\CacheHit', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.write', 'Illuminate\\Cache\\Events\\KeyWritten', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.delete', 'Illuminate\\Cache\\Events\\KeyForgotten', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.query', 'Illuminate\\Database\\Events\\QueryExecuted', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.before', 'Illuminate\\Queue\\Events\\JobProcessing', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.after', 'Illuminate\\Queue\\Events\\JobProcessed', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.failed', 'Illuminate\\Queue\\Events\\JobFailed', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.stopping', 'Illuminate\\Queue\\Events\\WorkerStopping', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('mailer.sending', 'Illuminate\\Mail\\Events\\MessageSending', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('router.matched', 'Illuminate\\Routing\\Events\\RouteMatched', 'class')])]]);
};
