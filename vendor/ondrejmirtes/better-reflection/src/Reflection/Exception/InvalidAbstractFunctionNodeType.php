<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\Roave\BetterReflection\Reflection\Exception;

use InvalidArgumentException;
use PhpParser\Node;
use _PhpScoper26e51eeacccf\Roave\BetterReflection\Reflection\ReflectionFunctionAbstract;
use function get_class;
use function sprintf;
class InvalidAbstractFunctionNodeType extends \InvalidArgumentException
{
    public static function fromNode(\PhpParser\Node $node) : self
    {
        return new self(\sprintf('Node for "%s" must be "%s" or "%s", was a "%s"', \_PhpScoper26e51eeacccf\Roave\BetterReflection\Reflection\ReflectionFunctionAbstract::class, \PhpParser\Node\Stmt\ClassMethod::class, \PhpParser\Node\FunctionLike::class, \get_class($node)));
    }
}
