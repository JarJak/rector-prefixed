<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\DependencyInjection;

use RectorPrefix20201227\_HumbugBox221ad6f1b81f\Nette\Schema\Expect;
use RectorPrefix20201227\PHPStan\Rules\RegistryFactory;
class RulesExtension extends \RectorPrefix20201227\_HumbugBox221ad6f1b81f\Nette\DI\CompilerExtension
{
    public function getConfigSchema() : \RectorPrefix20201227\_HumbugBox221ad6f1b81f\Nette\Schema\Schema
    {
        return \RectorPrefix20201227\_HumbugBox221ad6f1b81f\Nette\Schema\Expect::listOf('string');
    }
    public function loadConfiguration() : void
    {
        /** @var mixed[] $config */
        $config = $this->config;
        $builder = $this->getContainerBuilder();
        foreach ($config as $key => $rule) {
            $builder->addDefinition($this->prefix((string) $key))->setFactory($rule)->setAutowired(\false)->addTag(\RectorPrefix20201227\PHPStan\Rules\RegistryFactory::RULE_TAG);
        }
    }
}
