<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa;

use _PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option;
use _PhpScoper0a2ac50786fa\Rector\Core\ValueObject\ProjectType;
use _PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\_PhpScoper0a2ac50786fa\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $containerConfigurator->import(__DIR__ . '/../packages/*/config/config.php');
    $containerConfigurator->import(__DIR__ . '/../rules/*/config/config.php');
    $containerConfigurator->import(__DIR__ . '/services.php');
    $containerConfigurator->import(__DIR__ . '/../utils/*/config/config.php', null, \true);
    $parameters = $containerConfigurator->parameters();
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::PATHS, []);
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::FILE_EXTENSIONS, ['php']);
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::AUTOLOAD_PATHS, []);
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::AUTO_IMPORT_NAMES, \false);
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::IMPORT_SHORT_CLASSES, \true);
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::IMPORT_DOC_BLOCKS, \true);
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::PHP_VERSION_FEATURES, null);
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::PROJECT_TYPE, \_PhpScoper0a2ac50786fa\Rector\Core\ValueObject\ProjectType::PROPRIETARY);
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::NESTED_CHAIN_METHOD_CALL_LIMIT, 30);
    $parameters->set(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::SKIP, []);
};
