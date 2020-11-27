<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\Nette\DI\Extensions;

use _PhpScoper26e51eeacccf\Nette;
use _PhpScoper26e51eeacccf\Nette\DI\DynamicParameter;
/**
 * Parameters.
 */
final class ParametersExtension extends \_PhpScoper26e51eeacccf\Nette\DI\CompilerExtension
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
        $resolver = new \_PhpScoper26e51eeacccf\Nette\DI\Resolver($builder);
        $generator = new \_PhpScoper26e51eeacccf\Nette\DI\PhpGenerator($builder);
        foreach ($this->dynamicParams as $key) {
            $params[$key] = \array_key_exists($key, $params) ? new \_PhpScoper26e51eeacccf\Nette\DI\DynamicParameter($generator->formatPhp('($this->parameters[?] \\?\\? ?)', $resolver->completeArguments(\_PhpScoper26e51eeacccf\Nette\DI\Helpers::filterArguments([$key, $params[$key]])))) : new \_PhpScoper26e51eeacccf\Nette\DI\DynamicParameter(\_PhpScoper26e51eeacccf\Nette\PhpGenerator\Helpers::format('$this->parameters[?]', $key));
        }
        $builder->parameters = \_PhpScoper26e51eeacccf\Nette\DI\Helpers::expand($params, $params, \true);
        // expand all except 'services'
        $slice = \array_diff_key($this->compilerConfig, ['services' => 1]);
        $slice = \_PhpScoper26e51eeacccf\Nette\DI\Helpers::expand($slice, $builder->parameters);
        $this->compilerConfig = $slice + $this->compilerConfig;
    }
    public function afterCompile(\_PhpScoper26e51eeacccf\Nette\PhpGenerator\ClassType $class)
    {
        $parameters = $this->getContainerBuilder()->parameters;
        \array_walk_recursive($parameters, function (&$val) : void {
            if ($val instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement || $val instanceof \_PhpScoper26e51eeacccf\Nette\DI\DynamicParameter) {
                $val = null;
            }
        });
        $cnstr = $class->getMethod('__construct');
        $cnstr->addBody('$this->parameters += ?;', [$parameters]);
        foreach ($this->dynamicValidators as [$param, $expected]) {
            if ($param instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement) {
                continue;
            }
            $cnstr->addBody('Nette\\Utils\\Validators::assert(?, ?, ?);', [$param, $expected, 'dynamic parameter']);
        }
    }
}
