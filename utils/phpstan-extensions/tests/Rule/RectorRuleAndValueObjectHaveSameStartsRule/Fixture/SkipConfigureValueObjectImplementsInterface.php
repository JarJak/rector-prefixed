<?php

declare (strict_types=1);
namespace Rector\PHPStanExtensions\Tests\Rule\RectorRuleAndValueObjectHaveSameStartsRule\Fixture;

use Rector\PHPStanExtensions\Tests\Rule\RectorRuleAndValueObjectHaveSameStartsRule\Source\ConfigureValueObjectWithInterface;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector::class)->call('configure', [[\Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector::STATIC_CALLS_TO_FUNCTIONS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\PHPStanExtensions\Tests\Rule\RectorRuleAndValueObjectHaveSameStartsRule\Source\ConfigureValueObjectWithInterface()])]]);
};
