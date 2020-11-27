<?php

declare (strict_types=1);
namespace _PhpScopera143bcca66cb;

use Rector\Generic\Rector\String_\StringToClassConstantRector;
use Rector\Generic\ValueObject\StringToClassConstant;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
# see: https://laravel.com/docs/5.2/upgrade
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScopera143bcca66cb\\Illuminate\\Auth\\Access\\UnauthorizedException' => '_PhpScopera143bcca66cb\\Illuminate\\Auth\\Access\\AuthorizationException', '_PhpScopera143bcca66cb\\Illuminate\\Http\\Exception\\HttpResponseException' => '_PhpScopera143bcca66cb\\Illuminate\\Foundation\\Validation\\ValidationException', '_PhpScopera143bcca66cb\\Illuminate\\Foundation\\Composer' => '_PhpScopera143bcca66cb\\Illuminate\\Support\\Composer']]]);
    $services->set(\Rector\Generic\Rector\String_\StringToClassConstantRector::class)->call('configure', [[\Rector\Generic\Rector\String_\StringToClassConstantRector::STRINGS_TO_CLASS_CONSTANTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\StringToClassConstant('artisan.start', '_PhpScopera143bcca66cb\\Illuminate\\Console\\Events\\ArtisanStarting', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('auth.attempting', '_PhpScopera143bcca66cb\\Illuminate\\Auth\\Events\\Attempting', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('auth.login', '_PhpScopera143bcca66cb\\Illuminate\\Auth\\Events\\Login', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('auth.logout', '_PhpScopera143bcca66cb\\Illuminate\\Auth\\Events\\Logout', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.missed', '_PhpScopera143bcca66cb\\Illuminate\\Cache\\Events\\CacheMissed', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.hit', '_PhpScopera143bcca66cb\\Illuminate\\Cache\\Events\\CacheHit', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.write', '_PhpScopera143bcca66cb\\Illuminate\\Cache\\Events\\KeyWritten', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('cache.delete', '_PhpScopera143bcca66cb\\Illuminate\\Cache\\Events\\KeyForgotten', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.query', '_PhpScopera143bcca66cb\\Illuminate\\Database\\Events\\QueryExecuted', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.before', '_PhpScopera143bcca66cb\\Illuminate\\Queue\\Events\\JobProcessing', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.after', '_PhpScopera143bcca66cb\\Illuminate\\Queue\\Events\\JobProcessed', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.failed', '_PhpScopera143bcca66cb\\Illuminate\\Queue\\Events\\JobFailed', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.queue.stopping', '_PhpScopera143bcca66cb\\Illuminate\\Queue\\Events\\WorkerStopping', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('mailer.sending', '_PhpScopera143bcca66cb\\Illuminate\\Mail\\Events\\MessageSending', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('router.matched', '_PhpScopera143bcca66cb\\Illuminate\\Routing\\Events\\RouteMatched', 'class')])]]);
};
