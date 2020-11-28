<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05;

use Rector\Generic\Rector\ClassMethod\ArgumentDefaultValueReplacerRector;
use Rector\Generic\ValueObject\ArgumentDefaultValueReplacer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Generic\Rector\ClassMethod\ArgumentDefaultValueReplacerRector::class)->call('configure', [[\Rector\Generic\Rector\ClassMethod\ArgumentDefaultValueReplacerRector::REPLACED_ARGUMENTS => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoperabd03f0baf05\\Symfony\\Component\\Yaml\\Yaml', 'parse', 1, [\false, \false, \true], 'Symfony\\Component\\Yaml\\Yaml::PARSE_OBJECT_FOR_MAP'), new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoperabd03f0baf05\\Symfony\\Component\\Yaml\\Yaml', 'parse', 1, [\false, \true], 'Symfony\\Component\\Yaml\\Yaml::PARSE_OBJECT'), new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoperabd03f0baf05\\Symfony\\Component\\Yaml\\Yaml', 'parse', 1, \true, 'Symfony\\Component\\Yaml\\Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE'), new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoperabd03f0baf05\\Symfony\\Component\\Yaml\\Yaml', 'parse', 1, \false, 0), new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoperabd03f0baf05\\Symfony\\Component\\Yaml\\Yaml', 'dump', 3, [\false, \true], 'Symfony\\Component\\Yaml\\Yaml::DUMP_OBJECT'), new \Rector\Generic\ValueObject\ArgumentDefaultValueReplacer('_PhpScoperabd03f0baf05\\Symfony\\Component\\Yaml\\Yaml', 'dump', 3, \true, 'Symfony\\Component\\Yaml\\Yaml::DUMP_EXCEPTION_ON_INVALID_TYPE')])]]);
};
