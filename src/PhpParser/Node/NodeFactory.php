<?php

declare (strict_types=1);
namespace Rector\Core\PhpParser\Node;

use PhpParser\BuilderFactory;
use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Const_;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Error;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\UnionType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\Exception\NotImplementedException;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\PhpParser\Builder\MethodBuilder;
use Rector\Core\PhpParser\Builder\ParamBuilder;
use Rector\Core\PhpParser\Builder\PropertyBuilder;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\StaticTypeMapper;
/**
 * @see \Rector\Core\Tests\PhpParser\Node\NodeFactoryTest
 */
final class NodeFactory
{
    /**
     * @var string
     */
    private const THIS = 'this';
    /**
     * @var BuilderFactory
     */
    private $builderFactory;
    /**
     * @var StaticTypeMapper
     */
    private $staticTypeMapper;
    /**
     * @var PhpDocInfoFactory
     */
    private $phpDocInfoFactory;
    /**
     * @var PhpVersionProvider
     */
    private $phpVersionProvider;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    public function __construct(\PhpParser\BuilderFactory $builderFactory, \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory $phpDocInfoFactory, \Rector\Core\Php\PhpVersionProvider $phpVersionProvider, \Rector\StaticTypeMapper\StaticTypeMapper $staticTypeMapper, \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver)
    {
        $this->builderFactory = $builderFactory;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->phpVersionProvider = $phpVersionProvider;
        $this->nodeNameResolver = $nodeNameResolver;
    }
    /**
     * Creates "SomeClass::CONSTANT"
     */
    public function createShortClassConstFetch(string $shortClassName, string $constantName) : \PhpParser\Node\Expr\ClassConstFetch
    {
        $name = new \PhpParser\Node\Name($shortClassName);
        return $this->createClassConstFetchFromName($name, $constantName);
    }
    /**
     * Creates "\SomeClass::CONSTANT"
     */
    public function createClassConstFetch(string $className, string $constantName) : \PhpParser\Node\Expr\ClassConstFetch
    {
        $classNameNode = \in_array($className, ['static', 'parent', 'self'], \true) ? new \PhpParser\Node\Name($className) : new \PhpParser\Node\Name\FullyQualified($className);
        return $this->createClassConstFetchFromName($classNameNode, $constantName);
    }
    /**
     * Creates "\SomeClass::class"
     */
    public function createClassConstReference(string $className) : \PhpParser\Node\Expr\ClassConstFetch
    {
        return $this->createClassConstFetch($className, 'class');
    }
    /**
     * Creates "['item', $variable]"
     *
     * @param mixed[] $items
     */
    public function createArray(array $items) : \PhpParser\Node\Expr\Array_
    {
        $arrayItems = [];
        $defaultKey = 0;
        foreach ($items as $key => $item) {
            $customKey = $key !== $defaultKey ? $key : null;
            $arrayItems[] = $this->createArrayItem($item, $customKey);
            ++$defaultKey;
        }
        return new \PhpParser\Node\Expr\Array_($arrayItems);
    }
    /**
     * Creates "($args)"
     *
     * @param mixed[] $values
     * @return Arg[]
     */
    public function createArgs(array $values) : array
    {
        $normalizedValues = [];
        foreach ($values as $key => $value) {
            $normalizedValues[$key] = $this->normalizeArgValue($value);
        }
        return $this->builderFactory->args($normalizedValues);
    }
    /**
     * Creates $this->property = $property;
     */
    public function createPropertyAssignment(string $propertyName) : \PhpParser\Node\Expr\Assign
    {
        $variable = new \PhpParser\Node\Expr\Variable($propertyName);
        return $this->createPropertyAssignmentWithExpr($propertyName, $variable);
    }
    public function createPropertyAssignmentWithExpr(string $propertyName, \PhpParser\Node\Expr $expr) : \PhpParser\Node\Expr\Assign
    {
        $propertyFetch = $this->createPropertyFetch(self::THIS, $propertyName);
        return new \PhpParser\Node\Expr\Assign($propertyFetch, $expr);
    }
    /**
     * @param mixed $argument
     */
    public function createArg($argument) : \PhpParser\Node\Arg
    {
        return new \PhpParser\Node\Arg(\PhpParser\BuilderHelpers::normalizeValue($argument));
    }
    public function createPublicMethod(string $name) : \PhpParser\Node\Stmt\ClassMethod
    {
        $methodBuilder = new \Rector\Core\PhpParser\Builder\MethodBuilder($name);
        $methodBuilder->makePublic();
        return $methodBuilder->getNode();
    }
    public function createParamFromNameAndType(string $name, ?\PHPStan\Type\Type $type) : \PhpParser\Node\Param
    {
        $paramBuilder = new \Rector\Core\PhpParser\Builder\ParamBuilder($name);
        if ($type !== null) {
            $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type);
            if ($typeNode !== null) {
                $paramBuilder->setType($typeNode);
            }
        }
        return $paramBuilder->getNode();
    }
    public function createPublicInjectPropertyFromNameAndType(string $name, ?\PHPStan\Type\Type $type) : \PhpParser\Node\Stmt\Property
    {
        $propertyBuilder = new \Rector\Core\PhpParser\Builder\PropertyBuilder($name);
        $propertyBuilder->makePublic();
        $property = $propertyBuilder->getNode();
        $this->addPropertyType($property, $type);
        $this->decorateParentPropertyProperty($property);
        // add @inject
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            $phpDocInfo = $this->phpDocInfoFactory->createFromNode($property);
        }
        $phpDocInfo->addBareTag('inject');
        return $property;
    }
    public function createPrivatePropertyFromNameAndType(string $name, ?\PHPStan\Type\Type $type) : \PhpParser\Node\Stmt\Property
    {
        $propertyBuilder = new \Rector\Core\PhpParser\Builder\PropertyBuilder($name);
        $propertyBuilder->makePrivate();
        $property = $propertyBuilder->getNode();
        $this->addPropertyType($property, $type);
        $this->decorateParentPropertyProperty($property);
        return $property;
    }
    /**
     * @param string|Expr $variable
     * @param mixed[] $arguments
     */
    public function createMethodCall($variable, string $method, array $arguments = []) : \PhpParser\Node\Expr\MethodCall
    {
        if (\is_string($variable)) {
            $variable = new \PhpParser\Node\Expr\Variable($variable);
        }
        if ($variable instanceof \PhpParser\Node\Expr\PropertyFetch) {
            $variable = new \PhpParser\Node\Expr\PropertyFetch($variable->var, $variable->name);
        }
        if ($variable instanceof \PhpParser\Node\Expr\StaticPropertyFetch) {
            $variable = new \PhpParser\Node\Expr\StaticPropertyFetch($variable->class, $variable->name);
        }
        if ($variable instanceof \PhpParser\Node\Expr\MethodCall) {
            $variable = new \PhpParser\Node\Expr\MethodCall($variable->var, $variable->name, $variable->args);
        }
        $methodCallNode = $this->builderFactory->methodCall($variable, $method, $arguments);
        $variable->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE, $methodCallNode);
        return $methodCallNode;
    }
    /**
     * @param string|Expr $variable
     */
    public function createPropertyFetch($variable, string $property) : \PhpParser\Node\Expr\PropertyFetch
    {
        if (\is_string($variable)) {
            $variable = new \PhpParser\Node\Expr\Variable($variable);
        }
        return $this->builderFactory->propertyFetch($variable, $property);
    }
    /**
     * @param Param[] $params
     */
    public function createParentConstructWithParams(array $params) : \PhpParser\Node\Expr\StaticCall
    {
        return new \PhpParser\Node\Expr\StaticCall(new \PhpParser\Node\Name('parent'), new \PhpParser\Node\Identifier(\Rector\Core\ValueObject\MethodName::CONSTRUCT), $this->convertParamNodesToArgNodes($params));
    }
    public function createStaticProtectedPropertyWithDefault(string $name, \PhpParser\Node $node) : \PhpParser\Node\Stmt\Property
    {
        $propertyBuilder = new \Rector\Core\PhpParser\Builder\PropertyBuilder($name);
        $propertyBuilder->makeProtected();
        $propertyBuilder->makeStatic();
        $propertyBuilder->setDefault($node);
        $property = $propertyBuilder->getNode();
        $this->decorateParentPropertyProperty($property);
        return $property;
    }
    public function createPrivateProperty(string $name) : \PhpParser\Node\Stmt\Property
    {
        $propertyBuilder = new \Rector\Core\PhpParser\Builder\PropertyBuilder($name);
        $propertyBuilder->makePrivate();
        $property = $propertyBuilder->getNode();
        $this->decorateParentPropertyProperty($property);
        $this->phpDocInfoFactory->createFromNode($property);
        return $property;
    }
    public function createPublicProperty(string $name) : \PhpParser\Node\Stmt\Property
    {
        $propertyBuilder = new \Rector\Core\PhpParser\Builder\PropertyBuilder($name);
        $propertyBuilder->makePublic();
        $property = $propertyBuilder->getNode();
        $this->decorateParentPropertyProperty($property);
        $this->phpDocInfoFactory->createFromNode($property);
        return $property;
    }
    /**
     * @param mixed $value
     */
    public function createPrivateClassConst(string $name, $value) : \PhpParser\Node\Stmt\ClassConst
    {
        return $this->createClassConstant($name, $value, \PhpParser\Node\Stmt\Class_::MODIFIER_PRIVATE);
    }
    /**
     * @param mixed $value
     */
    public function createPublicClassConst(string $name, $value) : \PhpParser\Node\Stmt\ClassConst
    {
        return $this->createClassConstant($name, $value, \PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC);
    }
    /**
     * @param Identifier|Name|NullableType|UnionType|null $typeNode
     */
    public function createGetterClassMethodFromNameAndType(string $propertyName, ?\PhpParser\Node $typeNode) : \PhpParser\Node\Stmt\ClassMethod
    {
        $getterMethod = 'get' . \ucfirst($propertyName);
        $methodBuilder = new \Rector\Core\PhpParser\Builder\MethodBuilder($getterMethod);
        $methodBuilder->makePublic();
        $propertyFetch = new \PhpParser\Node\Expr\PropertyFetch(new \PhpParser\Node\Expr\Variable(self::THIS), $propertyName);
        $return = new \PhpParser\Node\Stmt\Return_($propertyFetch);
        $methodBuilder->addStmt($return);
        if ($typeNode !== null) {
            $methodBuilder->setReturnType($typeNode);
        }
        return $methodBuilder->getNode();
    }
    /**
     * @todo decouple to StackNodeFactory
     * @param Expr[] $exprs
     */
    public function createConcat(array $exprs) : ?\PhpParser\Node\Expr\BinaryOp\Concat
    {
        if (\count($exprs) < 2) {
            return null;
        }
        /** @var Expr $previousConcat */
        $previousConcat = \array_shift($exprs);
        foreach ($exprs as $expr) {
            $previousConcat = new \PhpParser\Node\Expr\BinaryOp\Concat($previousConcat, $expr);
        }
        /** @var Concat $previousConcat */
        return $previousConcat;
    }
    public function createClosureFromClassMethod(\PhpParser\Node\Stmt\ClassMethod $classMethod) : \PhpParser\Node\Expr\Closure
    {
        $classMethodName = $this->nodeNameResolver->getName($classMethod);
        $args = $this->createArgs($classMethod->params);
        $methodCall = new \PhpParser\Node\Expr\MethodCall(new \PhpParser\Node\Expr\Variable(self::THIS), $classMethodName, $args);
        $return = new \PhpParser\Node\Stmt\Return_($methodCall);
        return new \PhpParser\Node\Expr\Closure(['params' => $classMethod->params, 'stmts' => [$return], 'returnType' => $classMethod->returnType]);
    }
    /**
     * @param string[] $names
     * @return Use_[]
     */
    public function createUsesFromNames(array $names) : array
    {
        $uses = [];
        foreach ($names as $resolvedName) {
            $useUse = new \PhpParser\Node\Stmt\UseUse(new \PhpParser\Node\Name($resolvedName));
            $uses[] = new \PhpParser\Node\Stmt\Use_([$useUse]);
        }
        return $uses;
    }
    public function createStaticCall(string $class, string $method) : \PhpParser\Node\Expr\StaticCall
    {
        return new \PhpParser\Node\Expr\StaticCall(new \PhpParser\Node\Name($class), $method);
    }
    /**
     * @param mixed[] $arguments
     */
    public function createFuncCall(string $name, array $arguments) : \PhpParser\Node\Expr\FuncCall
    {
        $arguments = $this->createArgs($arguments);
        return new \PhpParser\Node\Expr\FuncCall(new \PhpParser\Node\Name($name), $arguments);
    }
    private function createClassConstFetchFromName(\PhpParser\Node\Name $className, string $constantName) : \PhpParser\Node\Expr\ClassConstFetch
    {
        $classConstFetchNode = $this->builderFactory->classConstFetch($className, $constantName);
        $classConstFetchNode->class->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::RESOLVED_NAME, (string) $className);
        return $classConstFetchNode;
    }
    /**
     * @param mixed $item
     * @param string|int|null $key
     */
    private function createArrayItem($item, $key = null) : \PhpParser\Node\Expr\ArrayItem
    {
        $arrayItem = null;
        if ($item instanceof \PhpParser\Node\Expr\Variable || $item instanceof \PhpParser\Node\Expr\MethodCall || $item instanceof \PhpParser\Node\Expr\StaticCall || $item instanceof \PhpParser\Node\Expr\FuncCall || $item instanceof \PhpParser\Node\Expr\BinaryOp\Concat || $item instanceof \PhpParser\Node\Scalar) {
            $arrayItem = new \PhpParser\Node\Expr\ArrayItem($item);
        } elseif ($item instanceof \PhpParser\Node\Identifier) {
            $string = new \PhpParser\Node\Scalar\String_($item->toString());
            $arrayItem = new \PhpParser\Node\Expr\ArrayItem($string);
        } elseif (\is_scalar($item) || $item instanceof \PhpParser\Node\Expr\Array_) {
            $itemValue = \PhpParser\BuilderHelpers::normalizeValue($item);
            $arrayItem = new \PhpParser\Node\Expr\ArrayItem($itemValue);
        } elseif (\is_array($item)) {
            $arrayItem = new \PhpParser\Node\Expr\ArrayItem($this->createArray($item));
        }
        if ($arrayItem !== null) {
            if ($key === null) {
                return $arrayItem;
            }
            $arrayItem->key = \PhpParser\BuilderHelpers::normalizeValue($key);
            return $arrayItem;
        }
        if ($item instanceof \PhpParser\Node\Expr\ClassConstFetch) {
            $itemValue = \PhpParser\BuilderHelpers::normalizeValue($item);
            return new \PhpParser\Node\Expr\ArrayItem($itemValue);
        }
        throw new \Rector\Core\Exception\NotImplementedException(\sprintf('Not implemented yet. Go to "%s()" and add check for "%s" node.', __METHOD__, \is_object($item) ? \get_class($item) : $item));
    }
    /**
     * @param mixed $value
     * @return mixed|Error|Variable
     */
    private function normalizeArgValue($value)
    {
        if ($value instanceof \PhpParser\Node\Param) {
            return $value->var;
        }
        return $value;
    }
    private function addPropertyType(\PhpParser\Node\Stmt\Property $property, ?\PHPStan\Type\Type $type) : void
    {
        if ($type === null) {
            return;
        }
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($property);
        if ($this->phpVersionProvider->isAtLeastPhpVersion(\Rector\Core\ValueObject\PhpVersionFeature::TYPED_PROPERTIES)) {
            $phpParserType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type);
            if ($phpParserType !== null) {
                $property->type = $phpParserType;
                if ($type instanceof \PHPStan\Type\Generic\GenericObjectType) {
                    $phpDocInfo->changeVarType($type);
                }
                return;
            }
        }
        $phpDocInfo->changeVarType($type);
    }
    private function decorateParentPropertyProperty(\PhpParser\Node\Stmt\Property $property) : void
    {
        // complete property property parent, needed for other operations
        $propertyProperty = $property->props[0];
        $propertyProperty->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE, $property);
    }
    /**
     * @param Param[] $paramNodes
     * @return Arg[]
     */
    private function convertParamNodesToArgNodes(array $paramNodes) : array
    {
        $args = [];
        foreach ($paramNodes as $paramNode) {
            $args[] = new \PhpParser\Node\Arg($paramNode->var);
        }
        return $args;
    }
    /**
     * @param mixed $value
     */
    private function createClassConstant(string $name, $value, int $modifier) : \PhpParser\Node\Stmt\ClassConst
    {
        $value = \PhpParser\BuilderHelpers::normalizeValue($value);
        $const = new \PhpParser\Node\Const_($name, $value);
        $classConst = new \PhpParser\Node\Stmt\ClassConst([$const]);
        $classConst->flags |= $modifier;
        // add @var type by default
        $staticType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($value);
        if (!$staticType instanceof \PHPStan\Type\MixedType) {
            $phpDocInfo = $this->phpDocInfoFactory->createEmpty($classConst);
            $phpDocInfo->changeVarType($staticType);
        }
        return $classConst;
    }
}