<?php

declare (strict_types=1);
namespace PHPStan\Reflection\BetterReflection\SourceLocator;

use _PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\Identifier;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\IdentifierType;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\Reflector;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\SourceLocator;
class PhpVersionBlacklistSourceLocator implements \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\SourceLocator
{
    /**
     * @var \Roave\BetterReflection\SourceLocator\Type\SourceLocator
     */
    private $sourceLocator;
    /**
     * @var \Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber
     */
    private $phpStormStubsSourceStubber;
    public function __construct(\_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\SourceLocator $sourceLocator, \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber $phpStormStubsSourceStubber)
    {
        $this->sourceLocator = $sourceLocator;
        $this->phpStormStubsSourceStubber = $phpStormStubsSourceStubber;
    }
    public function locateIdentifier(\_PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\Identifier $identifier) : ?\_PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\Reflection
    {
        if ($identifier->isClass()) {
            if ($this->phpStormStubsSourceStubber->isPresentClass($identifier->getName()) === \false) {
                return null;
            }
        }
        if ($identifier->isFunction()) {
            if ($this->phpStormStubsSourceStubber->isPresentFunction($identifier->getName()) === \false) {
                return null;
            }
        }
        return $this->sourceLocator->locateIdentifier($reflector, $identifier);
    }
    public function locateIdentifiersByType(\_PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\IdentifierType $identifierType) : array
    {
        return $this->sourceLocator->locateIdentifiersByType($reflector, $identifierType);
    }
}