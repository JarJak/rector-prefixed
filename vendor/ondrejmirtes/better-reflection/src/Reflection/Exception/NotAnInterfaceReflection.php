<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\Exception;

use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionClass;
use UnexpectedValueException;
use function sprintf;
class NotAnInterfaceReflection extends \UnexpectedValueException
{
    public static function fromReflectionClass(\_PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionClass $class) : self
    {
        $type = 'class';
        if ($class->isTrait()) {
            $type = 'trait';
        }
        return new self(\sprintf('Provided node "%s" is not interface, but "%s"', $class->getName(), $type));
    }
}