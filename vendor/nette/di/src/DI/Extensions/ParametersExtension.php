<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoperabd03f0baf05\Nette\DI\Extensions;

use _PhpScoperabd03f0baf05\Nette;
use _PhpScoperabd03f0baf05\Nette\DI\DynamicParameter;
/**
 * Parameters.
 */
final class ParametersExtension extends \_PhpScoperabd03f0baf05\Nette\DI\CompilerExtension
{
    /** @var string[] */
    public $dynamicParams = [];
    /** @var string[][] */
    public $dynamicValidators = [];
    /** @var array */
    private $compilerConfig;
    public function __construct(array &$compilerConfig)
    {
        $this->compilerConfig =& $compilerConfig;
    }
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $params = $this->config;
        $resolver = new \_PhpScoperabd03f0baf05\Nette\DI\Resolver($builder);
        $generator = new \_PhpScoperabd03f0baf05\Nette\DI\PhpGenerator($builder);
        foreach ($this->dynamicParams as $key) {
            $params[$key] = \array_key_exists($key, $params) ? new \_PhpScoperabd03f0baf05\Nette\DI\DynamicParameter($generator->formatPhp('($this->parameters[?] \\?\\? ?)', $resolver->completeArguments(\_PhpScoperabd03f0baf05\Nette\DI\Helpers::filterArguments([$key, $params[$key]])))) : new \_PhpScoperabd03f0baf05\Nette\DI\DynamicParameter(\_PhpScoperabd03f0baf05\Nette\PhpGenerator\Helpers::format('$this->parameters[?]', $key));
        }
        $builder->parameters = \_PhpScoperabd03f0baf05\Nette\DI\Helpers::expand($params, $params, \true);
        // expand all except 'services'
        $slice = \array_diff_key($this->compilerConfig, ['services' => 1]);
        $slice = \_PhpScoperabd03f0baf05\Nette\DI\Helpers::expand($slice, $builder->parameters);
        $this->compilerConfig = $slice + $this->compilerConfig;
    }
    public function afterCompile(\_PhpScoperabd03f0baf05\Nette\PhpGenerator\ClassType $class)
    {
        $parameters = $this->getContainerBuilder()->parameters;
        \array_walk_recursive($parameters, function (&$val) : void {
            if ($val instanceof \_PhpScoperabd03f0baf05\Nette\DI\Definitions\Statement || $val instanceof \_PhpScoperabd03f0baf05\Nette\DI\DynamicParameter) {
                $val = null;
            }
        });
        $cnstr = $class->getMethod('__construct');
        $cnstr->addBody('$this->parameters += ?;', [$parameters]);
        foreach ($this->dynamicValidators as [$param, $expected]) {
            if ($param instanceof \_PhpScoperabd03f0baf05\Nette\DI\Definitions\Statement) {
                continue;
            }
            $cnstr->addBody('Nette\\Utils\\Validators::assert(?, ?, ?);', [$param, $expected, 'dynamic parameter']);
        }
    }
}
