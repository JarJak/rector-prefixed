<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\VendorLocker\NodeVendorLocker;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Rector\Privatization\VisibilityGuard\ClassMethodVisibilityGuard;
/**
 * @deprecated
 * Merge with @see ClassMethodVisibilityGuard
 */
final class ClassMethodVisibilityVendorLockResolver extends \_PhpScoper0a2ac50786fa\Rector\VendorLocker\NodeVendorLocker\AbstractNodeVendorLockResolver
{
    /**
     * Checks for:
     * - interface required methods
     * - abstract classes required method
     * - child classes required method
     *
     * Prevents:
     * - changing visibility conflicting with children
     */
    public function isParentLockedMethod(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        /** @var string $className */
        $className = $classMethod->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        if ($this->isInterfaceMethod($classMethod, $className)) {
            return \true;
        }
        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);
        return $this->hasParentMethod($className, $methodName);
    }
    public function isChildLockedMethod(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        /** @var string $className */
        $className = $classMethod->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($classMethod);
        return $this->hasChildMethod($className, $methodName);
    }
    private function isInterfaceMethod(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod, string $className) : bool
    {
        $interfaceMethodNames = $this->getInterfaceMethodNames($className);
        return $this->nodeNameResolver->isNames($classMethod, $interfaceMethodNames);
    }
    private function hasParentMethod(string $className, string $methodName) : bool
    {
        /** @var string[] $parentClasses */
        $parentClasses = (array) \class_parents($className);
        foreach ($parentClasses as $parentClass) {
            if (!\method_exists($parentClass, $methodName)) {
                continue;
            }
            return \true;
        }
        return \false;
    }
    private function hasChildMethod(string $desiredClassName, string $methodName) : bool
    {
        foreach (\get_declared_classes() as $className) {
            if ($className === $desiredClassName) {
                continue;
            }
            if (!\is_a($className, $desiredClassName, \true)) {
                continue;
            }
            if (\method_exists($className, $methodName)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @return string[]
     */
    private function getInterfaceMethodNames(string $className) : array
    {
        /** @var string[] $interfaces */
        $interfaces = (array) \class_implements($className);
        $interfaceMethods = [];
        foreach ($interfaces as $interface) {
            $interfaceMethods = \array_merge($interfaceMethods, \get_class_methods($interface));
        }
        return $interfaceMethods;
    }
}
