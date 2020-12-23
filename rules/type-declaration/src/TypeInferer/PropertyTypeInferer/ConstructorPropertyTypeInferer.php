<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use _PhpScoper0a2ac50786fa\Nette\Utils\Strings;
use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Name;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Name\FullyQualified;
use _PhpScoper0a2ac50786fa\PhpParser\Node\NullableType;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Param;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property;
use _PhpScoper0a2ac50786fa\PhpParser\NodeTraverser;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\MixedType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\NullType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\Manipulator\ClassMethodPropertyFetchManipulator;
use _PhpScoper0a2ac50786fa\Rector\Core\ValueObject\MethodName;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Rector\PHPStan\Type\AliasedObjectType;
use _PhpScoper0a2ac50786fa\Rector\PHPStan\Type\FullyQualifiedObjectType;
use _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Contract\TypeInferer\PropertyTypeInfererInterface;
use _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\TypeInferer\AbstractTypeInferer;
final class ConstructorPropertyTypeInferer extends \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\TypeInferer\AbstractTypeInferer implements \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Contract\TypeInferer\PropertyTypeInfererInterface
{
    /**
     * @var ClassMethodPropertyFetchManipulator
     */
    private $classMethodPropertyFetchManipulator;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\Manipulator\ClassMethodPropertyFetchManipulator $classMethodPropertyFetchManipulator)
    {
        $this->classMethodPropertyFetchManipulator = $classMethodPropertyFetchManipulator;
    }
    public function inferProperty(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property $property) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        /** @var Class_|null $classLike */
        $classLike = $property->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            // anonymous class
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
        }
        $classMethod = $classLike->getMethod(\_PhpScoper0a2ac50786fa\Rector\Core\ValueObject\MethodName::CONSTRUCT);
        if ($classMethod === null) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
        }
        $propertyName = $this->nodeNameResolver->getName($property);
        $param = $this->classMethodPropertyFetchManipulator->resolveParamForPropertyFetch($classMethod, $propertyName);
        if ($param === null) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
        }
        // A. infer from type declaration of parameter
        if ($param->type !== null) {
            return $this->resolveFromParamType($param, $classMethod, $propertyName);
        }
        return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
    }
    public function getPriority() : int
    {
        return 800;
    }
    private function resolveFromParamType(\_PhpScoper0a2ac50786fa\PhpParser\Node\Param $param, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod, string $propertyName) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        $type = $this->resolveParamTypeToPHPStanType($param);
        if ($type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
        }
        $types = [];
        // it's an array - annotation → make type more precise, if possible
        if ($type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType) {
            $types[] = $this->getResolveParamStaticTypeAsPHPStanType($classMethod, $propertyName);
        } else {
            $types[] = $type;
        }
        if ($this->isParamNullable($param)) {
            $types[] = new \_PhpScoper0a2ac50786fa\PHPStan\Type\NullType();
        }
        return $this->typeFactory->createMixedPassedOrUnionType($types);
    }
    private function resolveParamTypeToPHPStanType(\_PhpScoper0a2ac50786fa\PhpParser\Node\Param $param) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        if ($param->type === null) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
        }
        if ($param->type instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\NullableType) {
            $types = [];
            $types[] = new \_PhpScoper0a2ac50786fa\PHPStan\Type\NullType();
            $types[] = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type->type);
            return $this->typeFactory->createMixedPassedOrUnionType($types);
        }
        // special case for alias
        if ($param->type instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Name\FullyQualified) {
            $type = $this->resolveFullyQualifiedOrAliasedObjectType($param);
            if ($type !== null) {
                return $type;
            }
        }
        return $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
    }
    private function getResolveParamStaticTypeAsPHPStanType(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod, string $propertyName) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        $paramStaticType = new \_PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType(new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType(), new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType());
        $this->callableNodeTraverser->traverseNodesWithCallable((array) $classMethod->stmts, function (\_PhpScoper0a2ac50786fa\PhpParser\Node $node) use($propertyName, &$paramStaticType) : ?int {
            if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable) {
                return null;
            }
            if (!$this->nodeNameResolver->isName($node, $propertyName)) {
                return null;
            }
            $paramStaticType = $this->nodeTypeResolver->getStaticType($node);
            return \_PhpScoper0a2ac50786fa\PhpParser\NodeTraverser::STOP_TRAVERSAL;
        });
        return $paramStaticType;
    }
    private function isParamNullable(\_PhpScoper0a2ac50786fa\PhpParser\Node\Param $param) : bool
    {
        if ($param->type instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\NullableType) {
            return \true;
        }
        if ($param->default !== null) {
            $defaultValueStaticType = $this->nodeTypeResolver->getStaticType($param->default);
            if ($defaultValueStaticType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\NullType) {
                return \true;
            }
        }
        return \false;
    }
    private function resolveFullyQualifiedOrAliasedObjectType(\_PhpScoper0a2ac50786fa\PhpParser\Node\Param $param) : ?\_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        if ($param->type === null) {
            return null;
        }
        $fullyQualifiedName = $this->nodeNameResolver->getName($param->type);
        if (!$fullyQualifiedName) {
            return null;
        }
        $originalName = $param->type->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NAME);
        if (!$originalName instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Name) {
            return null;
        }
        // if the FQN has different ending than the original, it was aliased and we need to return the alias
        if (!\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::endsWith($fullyQualifiedName, '\\' . $originalName->toString())) {
            $className = $originalName->toString();
            if (\class_exists($className)) {
                return new \_PhpScoper0a2ac50786fa\Rector\PHPStan\Type\FullyQualifiedObjectType($className);
            }
            // @note: $fullyQualifiedName is a guess, needs real life test
            return new \_PhpScoper0a2ac50786fa\Rector\PHPStan\Type\AliasedObjectType($originalName->toString(), $fullyQualifiedName);
        }
        return null;
    }
}
