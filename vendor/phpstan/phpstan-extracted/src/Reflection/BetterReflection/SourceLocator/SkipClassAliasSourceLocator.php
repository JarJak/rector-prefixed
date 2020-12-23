<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Reflection\BetterReflection\SourceLocator;

use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Identifier\Identifier;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Identifier\IdentifierType;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflector\Reflector;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\SourceLocator;
class SkipClassAliasSourceLocator implements \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\SourceLocator
{
    /** @var SourceLocator */
    private $sourceLocator;
    public function __construct(\_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\SourceLocator $sourceLocator)
    {
        $this->sourceLocator = $sourceLocator;
    }
    public function locateIdentifier(\_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Identifier\Identifier $identifier) : ?\_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Reflection
    {
        if ($identifier->isClass()) {
            $className = $identifier->getName();
            if (!\class_exists($className, \false)) {
                return $this->sourceLocator->locateIdentifier($reflector, $identifier);
            }
            $reflection = new \ReflectionClass($className);
            if ($reflection->getFileName() === \false) {
                return $this->sourceLocator->locateIdentifier($reflector, $identifier);
            }
            return null;
        }
        return $this->sourceLocator->locateIdentifier($reflector, $identifier);
    }
    public function locateIdentifiersByType(\_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Identifier\IdentifierType $identifierType) : array
    {
        return $this->sourceLocator->locateIdentifiersByType($reflector, $identifierType);
    }
}
