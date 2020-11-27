<?php

declare (strict_types=1);
namespace _PhpScopera143bcca66cb\Roave\BetterReflection\Util\Autoload\ClassLoaderMethod\Exception;

use _PhpScopera143bcca66cb\Roave\BetterReflection\Reflection\ReflectionClass;
use RuntimeException;
use function sprintf;
final class SignatureCheckFailed extends \RuntimeException
{
    public static function fromReflectionClass(\_PhpScopera143bcca66cb\Roave\BetterReflection\Reflection\ReflectionClass $reflectionClass) : self
    {
        return new self(\sprintf('Failed to verify the signature of the cached file for %s', $reflectionClass->getName()));
    }
}
