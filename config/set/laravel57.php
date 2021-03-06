<?php

declare (strict_types=1);
namespace RectorPrefix20210118;

use Rector\Core\ValueObject\Visibility;
use Rector\Generic\NodeAnalyzer\ArgumentAddingScope;
use Rector\Generic\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Generic\Rector\ClassMethod\ArgumentRemoverRector;
use Rector\Generic\Rector\ClassMethod\ChangeMethodVisibilityRector;
use Rector\Generic\ValueObject\ArgumentAdder;
use Rector\Generic\ValueObject\ArgumentRemover;
use Rector\Generic\ValueObject\ChangeMethodVisibility;
use Rector\Laravel\Rector\Class_\AddMockConsoleOutputFalseToConsoleTestsRector;
use Rector\Laravel\Rector\ClassMethod\AddParentBootToModelClassMethodRector;
use Rector\Laravel\Rector\MethodCall\ChangeQueryWhereDateValueWithCarbonRector;
use Rector\Laravel\Rector\New_\AddGuardToLoginEventRector;
use Rector\Laravel\Rector\StaticCall\Redirect301ToPermanentRedirectRector;
use RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
# see: https://laravel.com/docs/5.7/upgrade
return static function (\RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Generic\Rector\ClassMethod\ChangeMethodVisibilityRector::class)->call('configure', [[\Rector\Generic\Rector\ClassMethod\ChangeMethodVisibilityRector::METHOD_VISIBILITIES => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\ChangeMethodVisibility('Illuminate\\Routing\\Router', 'addRoute', \Rector\Core\ValueObject\Visibility::PUBLIC), new \Rector\Generic\ValueObject\ChangeMethodVisibility('Illuminate\\Contracts\\Auth\\Access\\Gate', 'raw', \Rector\Core\ValueObject\Visibility::PUBLIC)])]]);
    $services->set(\Rector\Generic\Rector\ClassMethod\ArgumentAdderRector::class)->call('configure', [[\Rector\Generic\NodeAnalyzer\ArgumentAddingScope::ADDED_ARGUMENTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\ArgumentAdder('Illuminate\\Auth\\Middleware\\Authenticate', 'authenticate', 0, 'request'), new \Rector\Generic\ValueObject\ArgumentAdder('Illuminate\\Foundation\\Auth\\ResetsPasswords', 'sendResetResponse', 0, 'request', null, 'Illuminate\\Http\\Illuminate\\Http'), new \Rector\Generic\ValueObject\ArgumentAdder('Illuminate\\Foundation\\Auth\\SendsPasswordResetEmails', 'sendResetLinkResponse', 0, 'request', null, 'Illuminate\\Http\\Illuminate\\Http'), new \Rector\Generic\ValueObject\ArgumentAdder('Illuminate\\Database\\ConnectionInterface', 'select', 2, 'useReadPdo', \true), new \Rector\Generic\ValueObject\ArgumentAdder('Illuminate\\Database\\ConnectionInterface', 'selectOne', 2, 'useReadPdo', \true)])]]);
    $services->set(\Rector\Laravel\Rector\StaticCall\Redirect301ToPermanentRedirectRector::class);
    $services->set(\Rector\Generic\Rector\ClassMethod\ArgumentRemoverRector::class)->call('configure', [[\Rector\Generic\Rector\ClassMethod\ArgumentRemoverRector::REMOVED_ARGUMENTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\ArgumentRemover('Illuminate\\Foundation\\Application', 'register', 1, ['name' => 'options'])])]]);
    $services->set(\Rector\Laravel\Rector\ClassMethod\AddParentBootToModelClassMethodRector::class);
    $services->set(\Rector\Laravel\Rector\MethodCall\ChangeQueryWhereDateValueWithCarbonRector::class);
    $services->set(\Rector\Laravel\Rector\Class_\AddMockConsoleOutputFalseToConsoleTestsRector::class);
    $services->set(\Rector\Laravel\Rector\New_\AddGuardToLoginEventRector::class);
};
