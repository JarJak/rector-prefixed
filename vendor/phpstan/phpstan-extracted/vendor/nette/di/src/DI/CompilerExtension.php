<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI;

use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette;
/**
 * Configurator compiling extension.
 */
abstract class CompilerExtension
{
    use Nette\SmartObject;
    /** @var Compiler */
    protected $compiler;
    /** @var string */
    protected $name;
    /** @var array|object */
    protected $config = [];
    /** @var Nette\PhpGenerator\Closure */
    protected $initialization;
    /** @return static */
    public function setCompiler(\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI\Compiler $compiler, string $name)
    {
        $this->initialization = new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\PhpGenerator\Closure();
        $this->compiler = $compiler;
        $this->name = $name;
        return $this;
    }
    /**
     * @param  array|object  $config
     * @return static
     */
    public function setConfig($config)
    {
        if (!\is_array($config) && !\is_object($config)) {
            throw new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\InvalidArgumentException();
        }
        $this->config = $config;
        return $this;
    }
    /**
     * Returns extension configuration.
     * @return array|object
     */
    public function getConfig()
    {
        return $this->config;
    }
    /**
     * Returns configuration schema.
     */
    public function getConfigSchema() : \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Schema\Schema
    {
        return \is_object($this->config) ? \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Schema\Expect::from($this->config) : \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Schema\Expect::array();
    }
    /**
     * Checks whether $config contains only $expected items and returns combined array.
     * @throws Nette\InvalidStateException
     * @deprecated  use getConfigSchema()
     */
    public function validateConfig(array $expected, array $config = null, string $name = null) : array
    {
        if (\func_num_args() === 1) {
            return $this->config = $this->validateConfig($expected, $this->config);
        }
        if ($extra = \array_diff_key((array) $config, $expected)) {
            $name = $name ? \str_replace('.', ' › ', $name) : $this->name;
            $hint = \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\Helpers::getSuggestion(\array_keys($expected), \key($extra));
            $extra = $hint ? \key($extra) : \implode("', '{$name} › ", \array_keys($extra));
            throw new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI\InvalidConfigurationException("Unknown configuration option '{$name} › {$extra}'" . ($hint ? ", did you mean '{$name} › {$hint}'?" : '.'));
        }
        return \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Schema\Helpers::merge($config, $expected);
    }
    public function getContainerBuilder() : \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI\ContainerBuilder
    {
        return $this->compiler->getContainerBuilder();
    }
    /**
     * Reads configuration from file.
     */
    public function loadFromFile(string $file) : array
    {
        $loader = $this->createLoader();
        $res = $loader->load($file);
        $this->compiler->addDependencies($loader->getDependencies());
        return $res;
    }
    /**
     * Loads list of service definitions from configuration.
     * Prefixes its names and replaces @extension with name in definition.
     */
    public function loadDefinitionsFromConfig(array $configList) : void
    {
        $res = [];
        foreach ($configList as $key => $config) {
            $key = \is_string($key) ? $this->name . '.' . $key : $key;
            $res[$key] = \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI\Helpers::prefixServiceName($config, $this->name);
        }
        $this->compiler->loadDefinitionsFromConfig($res);
    }
    protected function createLoader() : \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI\Config\Loader
    {
        return new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI\Config\Loader();
    }
    public function getInitialization() : \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\PhpGenerator\Closure
    {
        return $this->initialization;
    }
    /**
     * Prepend extension name to identifier or service name.
     */
    public function prefix(string $id) : string
    {
        return \substr_replace($id, $this->name . '.', \substr($id, 0, 1) === '@' ? 1 : 0, 0);
    }
    /**
     * Processes configuration data. Intended to be overridden by descendant.
     * @return void
     */
    public function loadConfiguration()
    {
    }
    /**
     * Adjusts DI container before is compiled to PHP class. Intended to be overridden by descendant.
     * @return void
     */
    public function beforeCompile()
    {
    }
    /**
     * Adjusts DI container compiled to PHP class. Intended to be overridden by descendant.
     * @return void
     */
    public function afterCompile(\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\PhpGenerator\ClassType $class)
    {
    }
}