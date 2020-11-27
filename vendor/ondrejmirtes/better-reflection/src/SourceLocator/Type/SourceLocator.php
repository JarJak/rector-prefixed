<?php

declare (strict_types=1);
namespace _PhpScopera143bcca66cb\Roave\BetterReflection\SourceLocator\Type;

use _PhpScopera143bcca66cb\Roave\BetterReflection\Identifier\Identifier;
use _PhpScopera143bcca66cb\Roave\BetterReflection\Identifier\IdentifierType;
use _PhpScopera143bcca66cb\Roave\BetterReflection\Reflection\Reflection;
use _PhpScopera143bcca66cb\Roave\BetterReflection\Reflector\Reflector;
interface SourceLocator
{
    /**
     * Locate some source code.
     *
     * This method should return a LocatedSource value object or `null` if the
     * SourceLocator is unable to locate the source.
     *
     * NOTE: A SourceLocator should *NOT* throw an exception if it is unable to
     * locate the identifier, it should simply return null. If an exception is
     * thrown, it will break the Generic Reflector.
     */
    public function locateIdentifier(\_PhpScopera143bcca66cb\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScopera143bcca66cb\Roave\BetterReflection\Identifier\Identifier $identifier) : ?\_PhpScopera143bcca66cb\Roave\BetterReflection\Reflection\Reflection;
    /**
     * Find all identifiers of a type
     *
     * @return Reflection[]
     */
    public function locateIdentifiersByType(\_PhpScopera143bcca66cb\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScopera143bcca66cb\Roave\BetterReflection\Identifier\IdentifierType $identifierType) : array;
}
