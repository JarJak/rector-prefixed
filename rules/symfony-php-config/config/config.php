<?php

declare (strict_types=1);
namespace _PhpScoper567b66d83109;

use _PhpScoper567b66d83109\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use _PhpScoper567b66d83109\Symfony\Component\Yaml\Parser;
use Symplify\PhpConfigPrinter\Printer\PhpParserPhpConfigPrinter;
use Symplify\PhpConfigPrinter\YamlToPhpConverter;
return static function (\_PhpScoper567b66d83109\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Rector\\SymfonyPhpConfig\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/Rector']);
    $services->set(\Symplify\PhpConfigPrinter\YamlToPhpConverter::class);
    $services->set(\_PhpScoper567b66d83109\Symfony\Component\Yaml\Parser::class);
    $services->set(\Symplify\PhpConfigPrinter\Printer\PhpParserPhpConfigPrinter::class);
};
