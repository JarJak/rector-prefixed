<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use Rector\Generic\Rector\ClassMethod\ArgumentDefaultValueReplacerRector;
use Rector\Generic\ValueObject\ArgumentDefaultValueReplacer;
use Rector\Symfony\Rector\StaticCall\ParseFileRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Symfony\Rector\StaticCall\ParseFileRector::class);
    $services->set(\Rector\Generic\Rector\ClassMethod\ArgumentDefaultValueReplacerRector::class)->call('configure', [[\Rector\Generic\Rector\ClassMethod\ArgumentDefaultValueReplacerRector::REPLACED_ARGUMENTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([
        // https://github.com/symfony/symfony/commit/912fc4de8fd6de1e5397be4a94d39091423e5188
        new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoper006a73f0e455\\Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface', 'generate', 2, \true, 'Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface::ABSOLUTE_URL'),
        new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoper006a73f0e455\\Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface', 'generate', 2, \false, 'Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface::ABSOLUTE_PATH'),
        new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoper006a73f0e455\\Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface', 'generate', 2, 'relative', 'Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface::RELATIVE_PATH'),
        new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoper006a73f0e455\\Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface', 'generate', 2, 'network', 'Symfony\\Component\\Routing\\Generator\\UrlGeneratorInterface::NETWORK_PATH'),
    ])]]);
};