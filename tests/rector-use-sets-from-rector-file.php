<?php
declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
$parameters = $containerConfigurator->parameters();

$parameters->set(Option::PATHS, ['tests/fixture-use-sets-from-rector-file']);

$parameters->set(Option::AUTOLOAD_PATHS, [
    __DIR__ . '/fixture-use-sets-from-rector-file',
]);

$parameters->set(Option::SETS, [SetList::TYPE_DECLARATION]);
};
