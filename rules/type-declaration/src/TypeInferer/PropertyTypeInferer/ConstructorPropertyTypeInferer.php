<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;

use RectorPrefix20210118\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\NodeTraverser;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use Rector\Core\PhpParser\Node\Manipulator\ClassMethodPropertyFetchManipulator;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\TypeDeclaration\Contract\TypeInferer\PropertyTypeInfererInterface;
use Rector\TypeDeclaration\TypeInferer\AbstractTypeInferer;
final class ConstructorPropertyTypeInferer extends \Rector\TypeDeclaration\TypeInferer\AbstractTypeInferer implements \Rector\TypeDeclaration\Contract\TypeInferer\PropertyTypeInfererInterface
{
    /**
     * @var ClassMethodPropertyFetchManipulator
     */
    private $classMethodPropertyFetchManipulator;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\ClassMethodPropertyFetchManipulator $classMethodPropertyFetchManipulator)
    {
        $this->classMethodPropertyFetchManipulator = $classMethodPropertyFetchManipulator;
    }
    public function inferProperty(\PhpParser\Node\Stmt\Property $property) : \PHPStan\Type\Type
    {
        /** @var Class_|null $classLike */
        $classLike = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            // anonymous class
            return new \PHPStan\Type\MixedType();
        }
        $classMethod = $classLike->getMethod(\Rector\Core\ValueObject\MethodName::CONSTRUCT);
        if ($classMethod === null) {
            return new \PHPStan\Type\MixedType();
        }
        $propertyName = $this->nodeNameResolver->getName($property);
        $param = $this->classMethodPropertyFetchManipulator->resolveParamForPropertyFetch($classMethod, $propertyName);
        if ($param === null) {
            return new \PHPStan\Type\MixedType();
        }
        // A. infer from type declaration of parameter
        if ($param->type !== null) {
            return $this->resolveFromParamType($param, $classMethod, $propertyName);
        }
        return new \PHPStan\Type\MixedType();
    }
    public function getPriority() : int
    {
        return 800;
    }
    private function resolveFromParamType(\PhpParser\Node\Param $param, \PhpParser\Node\Stmt\ClassMethod $classMethod, string $propertyName) : \PHPStan\Type\Type
    {
        $type = $this->resolveParamTypeToPHPStanType($param);
        if ($type instanceof \PHPStan\Type\MixedType) {
            return new \PHPStan\Type\MixedType();
        }
        $types = [];
        // it's an array - annotation → make type more precise, if possible
        if ($type instanceof \PHPStan\Type\ArrayType) {
            $types[] = $this->getResolveParamStaticTypeAsPHPStanType($classMethod, $propertyName);
        } else {
            $types[] = $type;
        }
        if ($this->isParamNullable($param)) {
            $types[] = new \PHPStan\Type\NullType();
        }
        return $this->typeFactory->createMixedPassedOrUnionType($types);
    }
    private function resolveParamTypeToPHPStanType(\PhpParser\Node\Param $param) : \PHPStan\Type\Type
    {
        if ($param->type === null) {
            return new \PHPStan\Type\MixedType();
        }
        if ($param->type instanceof \PhpParser\Node\NullableType) {
            $types = [];
            $types[] = new \PHPStan\Type\NullType();
            $types[] = $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type->type);
            return $this->typeFactory->createMixedPassedOrUnionType($types);
        }
        // special case for alias
        if ($param->type instanceof \PhpParser\Node\Name\FullyQualified) {
            $type = $this->resolveFullyQualifiedOrAliasedObjectType($param);
            if ($type !== null) {
                return $type;
            }
        }
        return $this->staticTypeMapper->mapPhpParserNodePHPStanType($param->type);
    }
    private function getResolveParamStaticTypeAsPHPStanType(\PhpParser\Node\Stmt\ClassMethod $classMethod, string $propertyName) : \PHPStan\Type\Type
    {
        $paramStaticType = new \PHPStan\Type\ArrayType(new \PHPStan\Type\MixedType(), new \PHPStan\Type\MixedType());
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable((array) $classMethod->stmts, function (\PhpParser\Node $node) use($propertyName, &$paramStaticType) : ?int {
            if (!$node instanceof \PhpParser\Node\Expr\Variable) {
                return null;
            }
            if (!$this->nodeNameResolver->isName($node, $propertyName)) {
                return null;
            }
            $paramStaticType = $this->nodeTypeResolver->getStaticType($node);
            return \PhpParser\NodeTraverser::STOP_TRAVERSAL;
        });
        return $paramStaticType;
    }
    private function isParamNullable(\PhpParser\Node\Param $param) : bool
    {
        if ($param->type instanceof \PhpParser\Node\NullableType) {
            return \true;
        }
        if ($param->default !== null) {
            $defaultValueStaticType = $this->nodeTypeResolver->getStaticType($param->default);
            if ($defaultValueStaticType instanceof \PHPStan\Type\NullType) {
                return \true;
            }
        }
        return \false;
    }
    private function resolveFullyQualifiedOrAliasedObjectType(\PhpParser\Node\Param $param) : ?\PHPStan\Type\Type
    {
        if ($param->type === null) {
            return null;
        }
        $fullyQualifiedName = $this->nodeNameResolver->getName($param->type);
        if (!$fullyQualifiedName) {
            return null;
        }
        $originalName = $param->type->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NAME);
        if (!$originalName instanceof \PhpParser\Node\Name) {
            return null;
        }
        // if the FQN has different ending than the original, it was aliased and we need to return the alias
        if (!\RectorPrefix20210118\Nette\Utils\Strings::endsWith($fullyQualifiedName, '\\' . $originalName->toString())) {
            $className = $originalName->toString();
            if (\class_exists($className)) {
                return new \Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType($className);
            }
            // @note: $fullyQualifiedName is a guess, needs real life test
            return new \Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType($originalName->toString(), $fullyQualifiedName);
        }
        return null;
    }
}
