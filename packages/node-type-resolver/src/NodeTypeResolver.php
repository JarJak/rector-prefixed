<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver;

use _PhpScoper0a2ac50786fa\Nette\Utils\Strings;
use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Arg;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\New_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Param;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Scalar;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property;
use _PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Accessory\NonEmptyArrayType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\BooleanType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\FloatType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\IntersectionType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\MixedType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\NullType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ObjectWithoutClassType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
use _PhpScoper0a2ac50786fa\PHPStan\Type\TypeUtils;
use _PhpScoper0a2ac50786fa\PHPStan\Type\TypeWithClassName;
use _PhpScoper0a2ac50786fa\PHPStan\Type\UnionType;
use _PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScoper0a2ac50786fa\Rector\Core\NodeAnalyzer\ClassNodeAnalyzer;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\NodeTypeCorrector\ParentClassLikeTypeCorrector;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\TypeAnalyzer\ArrayTypeAnalyzer;
use _PhpScoper0a2ac50786fa\Rector\PHPStan\Type\FullyQualifiedObjectType;
use _PhpScoper0a2ac50786fa\Rector\PHPStan\TypeFactoryStaticHelper;
use _PhpScoper0a2ac50786fa\Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper;
use _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\PHPStan\Type\ObjectTypeSpecifier;
final class NodeTypeResolver
{
    /**
     * @var array<class-string, NodeTypeResolverInterface>
     */
    private $nodeTypeResolvers = [];
    /**
     * @var ObjectTypeSpecifier
     */
    private $objectTypeSpecifier;
    /**
     * @var ArrayTypeAnalyzer
     */
    private $arrayTypeAnalyzer;
    /**
     * @var ParentClassLikeTypeCorrector
     */
    private $parentClassLikeTypeCorrector;
    /**
     * @var TypeUnwrapper
     */
    private $typeUnwrapper;
    /**
     * @var ClassNodeAnalyzer
     */
    private $classNodeAnalyzer;
    /**
     * @param NodeTypeResolverInterface[] $nodeTypeResolvers
     */
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\PHPStan\Type\ObjectTypeSpecifier $objectTypeSpecifier, \_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\NodeTypeCorrector\ParentClassLikeTypeCorrector $parentClassLikeTypeCorrector, \_PhpScoper0a2ac50786fa\Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper $typeUnwrapper, \_PhpScoper0a2ac50786fa\Rector\Core\NodeAnalyzer\ClassNodeAnalyzer $classNodeAnalyzer, array $nodeTypeResolvers)
    {
        foreach ($nodeTypeResolvers as $nodeTypeResolver) {
            $this->addNodeTypeResolver($nodeTypeResolver);
        }
        $this->objectTypeSpecifier = $objectTypeSpecifier;
        $this->parentClassLikeTypeCorrector = $parentClassLikeTypeCorrector;
        $this->typeUnwrapper = $typeUnwrapper;
        $this->classNodeAnalyzer = $classNodeAnalyzer;
    }
    /**
     * Prevents circular dependency
     * @required
     */
    public function autowireNodeTypeResolver(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\TypeAnalyzer\ArrayTypeAnalyzer $arrayTypeAnalyzer) : void
    {
        $this->arrayTypeAnalyzer = $arrayTypeAnalyzer;
    }
    /**
     * @param ObjectType|string|mixed $requiredType
     */
    public function isObjectType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, $requiredType) : bool
    {
        $this->ensureRequiredTypeIsStringOrObjectType($requiredType, __METHOD__);
        if (\is_string($requiredType) && \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::contains($requiredType, '*')) {
            return $this->isFnMatch($node, $requiredType);
        }
        $resolvedType = $this->resolve($node);
        if ($resolvedType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType) {
            return \false;
        }
        // this should also work with ObjectType and UnionType with ObjectType
        // use PHPStan types here
        if (\is_string($requiredType)) {
            $requiredType = new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType($requiredType);
        }
        if ($resolvedType->equals($requiredType)) {
            return \true;
        }
        return $this->isMatchingUnionType($requiredType, $resolvedType);
    }
    public function resolve(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        $type = $this->resolveFirstType($node);
        if (!$type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\TypeWithClassName) {
            return $type;
        }
        return $this->parentClassLikeTypeCorrector->correct($type);
    }
    /**
     * e.g. string|null, ObjectNull|null
     */
    public function isNullableType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool
    {
        $nodeType = $this->resolve($node);
        if (!$nodeType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\UnionType) {
            return \false;
        }
        return $nodeType->isSuperTypeOf(new \_PhpScoper0a2ac50786fa\PHPStan\Type\NullType())->yes();
    }
    /**
     * @deprecated
     * Use @see NodeTypeResolver::resolve() instead
     */
    public function getStaticType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        if ($this->isArrayExpr($node)) {
            /** @var Expr $node */
            return $this->resolveArrayType($node);
        }
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Arg) {
            $node = $node->value;
        }
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Param || $node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar) {
            return $this->resolve($node);
        }
        /** @var Scope|null $nodeScope */
        $nodeScope = $node->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE);
        if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr || $nodeScope === null) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
        }
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\New_ && $this->classNodeAnalyzer->isAnonymousClass($node->class)) {
            return $this->resolveAnonymousClassType($node);
        }
        $staticType = $nodeScope->getType($node);
        if (!$staticType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType) {
            return $staticType;
        }
        return $this->objectTypeSpecifier->narrowToFullyQualifiedOrAliasedObjectType($node, $staticType);
    }
    public function isNumberType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool
    {
        return $this->isStaticType($node, \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType::class) || $this->isStaticType($node, \_PhpScoper0a2ac50786fa\PHPStan\Type\FloatType::class);
    }
    public function isStaticType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, string $staticTypeClass) : bool
    {
        if (!\is_a($staticTypeClass, \_PhpScoper0a2ac50786fa\PHPStan\Type\Type::class, \true)) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException(\sprintf('"%s" in "%s()" must be type of "%s"', $staticTypeClass, __METHOD__, \_PhpScoper0a2ac50786fa\PHPStan\Type\Type::class));
        }
        return \is_a($this->resolve($node), $staticTypeClass);
    }
    /**
     * @param ObjectType|string $desiredType
     */
    public function isObjectTypeOrNullableObjectType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, $desiredType) : bool
    {
        if ($this->isObjectType($node, $desiredType)) {
            return \true;
        }
        $nodeType = $this->getStaticType($node);
        if (!$nodeType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\UnionType) {
            return \false;
        }
        $unwrappedNodeType = $this->typeUnwrapper->unwrapNullableType($nodeType);
        if (!$unwrappedNodeType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\TypeWithClassName) {
            return \false;
        }
        $desiredTypeString = $desiredType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType ? $desiredType->getClassName() : $desiredType;
        return \is_a($unwrappedNodeType->getClassName(), $desiredTypeString, \true);
    }
    public function isNullableObjectType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool
    {
        return $this->isNullableTypeOfSpecificType($node, \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType::class);
    }
    public function isNullableArrayType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool
    {
        return $this->isNullableTypeOfSpecificType($node, \_PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType::class);
    }
    public function isNullableTypeOfSpecificType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, string $desiredType) : bool
    {
        $nodeType = $this->resolve($node);
        if (!$nodeType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\UnionType) {
            return \false;
        }
        if ($nodeType->isSuperTypeOf(new \_PhpScoper0a2ac50786fa\PHPStan\Type\NullType())->no()) {
            return \false;
        }
        if (\count($nodeType->getTypes()) !== 2) {
            return \false;
        }
        foreach ($nodeType->getTypes() as $type) {
            if (\is_a($type, $desiredType, \true)) {
                return \true;
            }
        }
        return \false;
    }
    public function isPropertyBoolean(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property $property) : bool
    {
        if ($this->isStaticType($property, \_PhpScoper0a2ac50786fa\PHPStan\Type\BooleanType::class)) {
            return \true;
        }
        $defaultNodeValue = $property->props[0]->default;
        if ($defaultNodeValue === null) {
            return \false;
        }
        return $this->isStaticType($defaultNodeValue, \_PhpScoper0a2ac50786fa\PHPStan\Type\BooleanType::class);
    }
    private function addNodeTypeResolver(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface $nodeTypeResolver) : void
    {
        foreach ($nodeTypeResolver->getNodeClasses() as $nodeClass) {
            $this->nodeTypeResolvers[$nodeClass] = $nodeTypeResolver;
        }
    }
    /**
     * @param ObjectType|string|mixed $requiredType
     */
    private function ensureRequiredTypeIsStringOrObjectType($requiredType, string $location) : void
    {
        if (\is_string($requiredType)) {
            return;
        }
        if ($requiredType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType) {
            return;
        }
        $reportedType = \is_object($requiredType) ? \get_class($requiredType) : $requiredType;
        throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException(\sprintf('Value passed to "%s()" must be string or "%s". "%s" given', $location, \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType::class, $reportedType));
    }
    private function isFnMatch(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, string $requiredType) : bool
    {
        $objectType = $this->resolve($node);
        $classNames = \_PhpScoper0a2ac50786fa\PHPStan\Type\TypeUtils::getDirectClassNames($objectType);
        foreach ($classNames as $className) {
            if (!\fnmatch($requiredType, $className, \FNM_NOESCAPE)) {
                continue;
            }
            return \true;
        }
        return \false;
    }
    private function isMatchingUnionType(\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $requiredType, \_PhpScoper0a2ac50786fa\PHPStan\Type\Type $resolvedType) : bool
    {
        if (!$resolvedType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\UnionType) {
            return \false;
        }
        foreach ($resolvedType->getTypes() as $unionedType) {
            if (!$unionedType->equals($requiredType)) {
                continue;
            }
            return \true;
        }
        return \false;
    }
    private function resolveFirstType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        $type = $this->resolveByNodeTypeResolvers($node);
        if ($type !== null) {
            return $type;
        }
        /** @var Scope|null $nodeScope */
        $nodeScope = $node->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE);
        if ($nodeScope === null || !$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
        }
        // skip anonymous classes, ref https://github.com/rectorphp/rector/issues/1574
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\New_ && $this->classNodeAnalyzer->isAnonymousClass($node->class)) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectWithoutClassType();
        }
        $type = $nodeScope->getType($node);
        // hot fix for phpstan not resolving chain method calls
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall && $type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType) {
            return $this->resolveFirstType($node->var);
        }
        return $type;
    }
    private function isArrayExpr(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool
    {
        if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr) {
            return \false;
        }
        return $this->arrayTypeAnalyzer->isArrayType($node);
    }
    private function resolveArrayType(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr $expr) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        /** @var Scope|null $scope */
        $scope = $expr->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE);
        if ($scope instanceof \_PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope) {
            $arrayType = $scope->getType($expr);
            if ($arrayType !== null) {
                return $this->removeNonEmptyArrayFromIntersectionWithArrayType($arrayType);
            }
        }
        return new \_PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType(new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType(), new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType());
    }
    private function resolveAnonymousClassType(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\New_ $new) : \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectWithoutClassType
    {
        if (!$new->class instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectWithoutClassType();
        }
        $types = [];
        /** @var Class_ $class */
        $class = $new->class;
        if ($class->extends !== null) {
            $parentClass = (string) $class->extends;
            $types[] = new \_PhpScoper0a2ac50786fa\Rector\PHPStan\Type\FullyQualifiedObjectType($parentClass);
        }
        foreach ((array) $class->implements as $implement) {
            $parentClass = (string) $implement;
            $types[] = new \_PhpScoper0a2ac50786fa\Rector\PHPStan\Type\FullyQualifiedObjectType($parentClass);
        }
        if (\count($types) > 1) {
            $unionType = \_PhpScoper0a2ac50786fa\Rector\PHPStan\TypeFactoryStaticHelper::createUnionObjectType($types);
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectWithoutClassType($unionType);
        }
        if (\count($types) === 1) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectWithoutClassType($types[0]);
        }
        return new \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectWithoutClassType();
    }
    private function resolveByNodeTypeResolvers(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        foreach ($this->nodeTypeResolvers as $nodeClass => $nodeTypeResolver) {
            if (!\is_a($node, $nodeClass)) {
                continue;
            }
            return $nodeTypeResolver->resolve($node);
        }
        return null;
    }
    private function removeNonEmptyArrayFromIntersectionWithArrayType(\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $type) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        if (!$type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\IntersectionType) {
            return $type;
        }
        if (\count($type->getTypes()) !== 2) {
            return $type;
        }
        if (!$type->isSubTypeOf(new \_PhpScoper0a2ac50786fa\PHPStan\Type\Accessory\NonEmptyArrayType())->yes()) {
            return $type;
        }
        $otherType = null;
        foreach ($type->getTypes() as $intersectionedType) {
            if ($intersectionedType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\Accessory\NonEmptyArrayType) {
                continue;
            }
            $otherType = $intersectionedType;
            break;
        }
        if ($otherType === null) {
            return $type;
        }
        return $otherType;
    }
}
