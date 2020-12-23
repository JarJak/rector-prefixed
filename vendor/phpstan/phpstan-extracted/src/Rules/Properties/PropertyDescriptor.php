<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Rules\Properties;

use _PhpScoper0a2ac50786fa\PHPStan\Reflection\PropertyReflection;
class PropertyDescriptor
{
    public function describePropertyByName(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\PropertyReflection $property, string $propertyName) : string
    {
        if (!$property->isStatic()) {
            return \sprintf('Property %s::$%s', $property->getDeclaringClass()->getDisplayName(), $propertyName);
        }
        return \sprintf('Static property %s::$%s', $property->getDeclaringClass()->getDisplayName(), $propertyName);
    }
    /**
     * @param \PHPStan\Reflection\PropertyReflection $property
     * @param \PhpParser\Node\Expr\PropertyFetch|\PhpParser\Node\Expr\StaticPropertyFetch $propertyFetch
     * @return string
     */
    public function describeProperty(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\PropertyReflection $property, $propertyFetch) : string
    {
        /** @var \PhpParser\Node\Identifier $name */
        $name = $propertyFetch->name;
        if (!$property->isStatic()) {
            return \sprintf('Property %s::$%s', $property->getDeclaringClass()->getDisplayName(), $name->name);
        }
        return \sprintf('Static property %s::$%s', $property->getDeclaringClass()->getDisplayName(), $name->name);
    }
}
