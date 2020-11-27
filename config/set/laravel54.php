<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use Rector\Generic\Rector\String_\StringToClassConstantRector;
use Rector\Generic\ValueObject\StringToClassConstant;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
# see: https://laravel.com/docs/5.4/upgrade
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Generic\Rector\String_\StringToClassConstantRector::class)->call('configure', [[\Rector\Generic\Rector\String_\StringToClassConstantRector::STRINGS_TO_CLASS_CONSTANTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\StringToClassConstant('kernel.handled', '_PhpScoper006a73f0e455\\Illuminate\\Foundation\\Http\\Events\\RequestHandled', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('locale.changed', '_PhpScoper006a73f0e455\\Illuminate\\Foundation\\Events\\LocaleUpdated', 'class'), new \Rector\Generic\ValueObject\StringToClassConstant('illuminate.log', '_PhpScoper006a73f0e455\\Illuminate\\Log\\Events\\MessageLogged', 'class')])]]);
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)->call('configure', [[\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => ['_PhpScoper006a73f0e455\\Illuminate\\Console\\AppNamespaceDetectorTrait' => '_PhpScoper006a73f0e455\\Illuminate\\Console\\DetectsApplicationNamespace', '_PhpScoper006a73f0e455\\Illuminate\\Http\\Exception\\HttpResponseException' => '_PhpScoper006a73f0e455\\Illuminate\\Http\\Exceptions\\HttpResponseException', '_PhpScoper006a73f0e455\\Illuminate\\Http\\Exception\\PostTooLargeException' => '_PhpScoper006a73f0e455\\Illuminate\\Http\\Exceptions\\PostTooLargeException', '_PhpScoper006a73f0e455\\Illuminate\\Foundation\\Http\\Middleware\\VerifyPostSize' => '_PhpScoper006a73f0e455\\Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize', '_PhpScoper006a73f0e455\\Symfony\\Component\\HttpFoundation\\Session\\SessionInterface' => '_PhpScoper006a73f0e455\\Illuminate\\Contracts\\Session\\Session']]]);
    $services->set(\Rector\Renaming\Rector\MethodCall\RenameMethodRector::class)->call('configure', [[\Rector\Renaming\Rector\MethodCall\RenameMethodRector::METHOD_CALL_RENAMES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Support\\Collection', 'every', 'nth'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany', 'setJoin', 'performJoin'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany', 'getRelatedIds', 'allRelatedIds'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Routing\\Router', 'middleware', 'aliasMiddleware'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Routing\\Route', 'getPath', 'uri'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Routing\\Route', 'getUri', 'uri'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Routing\\Route', 'getMethods', 'methods'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Routing\\Route', 'getParameter', 'parameter'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Contracts\\Session\\Session', 'set', 'put'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Contracts\\Session\\Session', 'getToken', 'token'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Support\\Facades\\Request', 'setSession', 'setLaravelSession'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Http\\Request', 'setSession', 'setLaravelSession'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Routing\\UrlGenerator', 'forceSchema', 'forceScheme'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Validation\\Validator', 'addError', 'addFailure'), new \Rector\Renaming\ValueObject\MethodCallRename('_PhpScoper006a73f0e455\\Illuminate\\Validation\\Validator', 'doReplacements', 'makeReplacements')])]]);
};
