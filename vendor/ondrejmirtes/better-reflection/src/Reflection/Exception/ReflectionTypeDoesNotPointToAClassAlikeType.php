<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\Exception;

use LogicException;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionType;
use function get_class;
use function sprintf;
class ReflectionTypeDoesNotPointToAClassAlikeType extends \LogicException
{
    public static function for(\_PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionType $type) : self
    {
        return new self(\sprintf('Provided %s instance does not point to a class-alike type, but to "%s"', \get_class($type), $type->__toString()));
    }
}