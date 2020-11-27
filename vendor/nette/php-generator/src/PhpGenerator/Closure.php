<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\Nette\PhpGenerator;

use _PhpScoper26e51eeacccf\Nette;
/**
 * Closure.
 *
 * @property string $body
 */
final class Closure
{
    use Nette\SmartObject;
    use Traits\FunctionLike;
    use Traits\AttributeAware;
    /** @var Parameter[] */
    private $uses = [];
    public static function from(\Closure $closure) : self
    {
        return (new \_PhpScoper26e51eeacccf\Nette\PhpGenerator\Factory())->fromFunctionReflection(new \ReflectionFunction($closure));
    }
    public function __toString() : string
    {
        try {
            return (new \_PhpScoper26e51eeacccf\Nette\PhpGenerator\Printer())->printClosure($this);
        } catch (\Throwable $e) {
            if (\PHP_VERSION_ID >= 70400) {
                throw $e;
            }
            \trigger_error('Exception in ' . __METHOD__ . "(): {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}", \E_USER_ERROR);
            return '';
        }
    }
    /**
     * @param  Parameter[]  $uses
     * @return static
     */
    public function setUses(array $uses) : self
    {
        (function (\_PhpScoper26e51eeacccf\Nette\PhpGenerator\Parameter ...$uses) {
        })(...$uses);
        $this->uses = $uses;
        return $this;
    }
    public function getUses() : array
    {
        return $this->uses;
    }
    public function addUse(string $name) : \_PhpScoper26e51eeacccf\Nette\PhpGenerator\Parameter
    {
        return $this->uses[] = new \_PhpScoper26e51eeacccf\Nette\PhpGenerator\Parameter($name);
    }
}
