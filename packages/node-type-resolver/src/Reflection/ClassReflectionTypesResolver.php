<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Reflection;

use _PhpScoper0a2ac50786fa\PHPStan\Reflection\ClassReflection;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\ReflectionProvider;
final class ClassReflectionTypesResolver
{
    /**
     * @var ReflectionProvider
     */
    private $reflectionProvider;
    public function __construct(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }
    /**
     * Warning: Includes original class as well to normalize all types to strings!
     *
     * @return string[]
     */
    public function resolve(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\ClassReflection $classReflection) : array
    {
        // current class
        $types = [$classReflection->getName()];
        // parent classes
        $types = \array_merge($types, $classReflection->getParentClassesNames());
        // interfaces
        foreach ($classReflection->getInterfaces() as $interfaceReflection) {
            $types[] = $interfaceReflection->getName();
        }
        foreach ($classReflection->getInterfaces() as $interfaceReflection) {
            $types[] = $interfaceReflection->getName();
        }
        // traits
        foreach ($classReflection->getTraits() as $traitReflection) {
            $types[] = $traitReflection->getName();
        }
        // to cover traits of parent classes
        foreach ($classReflection->getParentClassesNames() as $parentClassName) {
            $parentClassReflection = $this->reflectionProvider->getClass($parentClassName);
            foreach ($parentClassReflection->getTraits() as $parentClassTrait) {
                $types[] = $parentClassTrait->getName();
            }
        }
        return \array_unique($types);
    }
}
