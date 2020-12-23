<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\Manipulator;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PropertyFetch;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\ReflectionProvider;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ErrorType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\MixedType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
use _PhpScoper0a2ac50786fa\PHPStan\Type\TypeWithClassName;
use _PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\NodeTypeResolver;
/**
 * Utils for PropertyFetch Node:
 * "$this->property"
 */
final class PropertyFetchManipulator
{
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    /**
     * @var ReflectionProvider
     */
    private $reflectionProvider;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver, \_PhpScoper0a2ac50786fa\PHPStan\Reflection\ReflectionProvider $reflectionProvider)
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->reflectionProvider = $reflectionProvider;
        $this->nodeNameResolver = $nodeNameResolver;
    }
    public function isPropertyToSelf(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PropertyFetch $propertyFetch) : bool
    {
        if (!$this->nodeNameResolver->isName($propertyFetch->var, 'this')) {
            return \false;
        }
        /** @var Class_|null $classLike */
        $classLike = $propertyFetch->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return \false;
        }
        foreach ($classLike->getProperties() as $property) {
            if (!$this->nodeNameResolver->areNamesEqual($property->props[0], $propertyFetch)) {
                continue;
            }
            return \true;
        }
        return \false;
    }
    public function isMagicOnType(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PropertyFetch $propertyFetch, \_PhpScoper0a2ac50786fa\PHPStan\Type\Type $type) : bool
    {
        $varNodeType = $this->nodeTypeResolver->resolve($propertyFetch);
        if ($varNodeType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\ErrorType) {
            return \true;
        }
        if ($varNodeType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType) {
            return \false;
        }
        if ($varNodeType->isSuperTypeOf($type)->yes()) {
            return \false;
        }
        $nodeName = $this->nodeNameResolver->getName($propertyFetch);
        if ($nodeName === null) {
            return \false;
        }
        return !$this->hasPublicProperty($propertyFetch, $nodeName);
    }
    /**
     * Matches:
     * "$this->someValue = $<variableName>;"
     */
    public function isVariableAssignToThisPropertyFetch(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, string $variableName) : bool
    {
        if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign) {
            return \false;
        }
        if (!$node->expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable) {
            return \false;
        }
        if (!$this->nodeNameResolver->isName($node->expr, $variableName)) {
            return \false;
        }
        return $this->isLocalPropertyFetch($node->var);
    }
    /**
     * @param string[] $propertyNames
     */
    public function isLocalPropertyOfNames(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, array $propertyNames) : bool
    {
        if (!$this->isLocalPropertyFetch($node)) {
            return \false;
        }
        /** @var PropertyFetch $node */
        return $this->nodeNameResolver->isNames($node->name, $propertyNames);
    }
    public function isLocalPropertyFetch(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool
    {
        if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PropertyFetch) {
            return \false;
        }
        return $this->nodeNameResolver->isName($node->var, 'this');
    }
    private function hasPublicProperty(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PropertyFetch $propertyFetch, string $propertyName) : bool
    {
        $nodeScope = $propertyFetch->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE);
        if ($nodeScope === null) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        $propertyFetchType = $nodeScope->getType($propertyFetch->var);
        if (!$propertyFetchType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\TypeWithClassName) {
            return \false;
        }
        $propertyFetchType = $propertyFetchType->getClassName();
        if (!$this->reflectionProvider->hasClass($propertyFetchType)) {
            return \false;
        }
        $classReflection = $this->reflectionProvider->getClass($propertyFetchType);
        if (!$classReflection->hasProperty($propertyName)) {
            return \false;
        }
        $propertyReflection = $classReflection->getProperty($propertyName, $nodeScope);
        return $propertyReflection->isPublic();
    }
}
