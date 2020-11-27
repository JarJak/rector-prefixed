<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Roave\BetterReflection\Reflector;

use _PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\Identifier;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\IdentifierType;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionConstant;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\SourceLocator;
use function assert;
class ConstantReflector implements \_PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\Reflector
{
    /** @var SourceLocator */
    private $sourceLocator;
    /** @var ClassReflector */
    private $classReflector;
    public function __construct(\_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\SourceLocator $sourceLocator, \_PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\ClassReflector $classReflector)
    {
        $this->sourceLocator = $sourceLocator;
        $this->classReflector = $classReflector;
    }
    /**
     * Create a ReflectionConstant for the specified $constantName.
     *
     * @return ReflectionConstant
     *
     * @throws IdentifierNotFound
     */
    public function reflect(string $constantName) : \_PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\Reflection
    {
        $identifier = new \_PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\Identifier($constantName, new \_PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\IdentifierType(\_PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\IdentifierType::IDENTIFIER_CONSTANT));
        $constantInfo = $this->sourceLocator->locateIdentifier($this->classReflector, $identifier);
        \assert($constantInfo instanceof \_PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionConstant || $constantInfo === null);
        if ($constantInfo === null) {
            throw \_PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\Exception\IdentifierNotFound::fromIdentifier($identifier);
        }
        return $constantInfo;
    }
    /**
     * Get all the constants available in the scope specified by the SourceLocator.
     *
     * @return array<int, ReflectionConstant>
     */
    public function getAllConstants() : array
    {
        /** @var array<int,ReflectionConstant> $allConstants */
        $allConstants = $this->sourceLocator->locateIdentifiersByType($this, new \_PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\IdentifierType(\_PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\IdentifierType::IDENTIFIER_CONSTANT));
        return $allConstants;
    }
}
