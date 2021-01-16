<?php

declare (strict_types=1);
namespace Rector\PHPUnit\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode;
use Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractPHPUnitRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PHPUnit\NodeFactory\DataProviderClassMethodFactory;
use Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider;
use Rector\PHPUnit\ValueObject\DataProviderClassMethodRecipe;
use Rector\PHPUnit\ValueObject\ParamAndArg;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use RectorPrefix20210116\Webmozart\Assert\Assert;
/**
 * @see \Rector\PHPUnit\Tests\Rector\Class_\ArrayArgumentInTestToDataProviderRector\ArrayArgumentInTestToDataProviderRectorTest
 *
 * @see why → https://blog.martinhujer.cz/how-to-use-data-providers-in-phpunit/
 */
final class ArrayArgumentInTestToDataProviderRector extends \Rector\Core\Rector\AbstractPHPUnitRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const ARRAY_ARGUMENTS_TO_DATA_PROVIDERS = 'array_arguments_to_data_providers';
    /**
     * @var ArrayArgumentToDataProvider[]
     */
    private $arrayArgumentsToDataProviders = [];
    /**
     * @var DataProviderClassMethodRecipe[]
     */
    private $dataProviderClassMethodRecipes = [];
    /**
     * @var DataProviderClassMethodFactory
     */
    private $dataProviderClassMethodFactory;
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    public function __construct(\Rector\PHPUnit\NodeFactory\DataProviderClassMethodFactory $dataProviderClassMethodFactory, \Rector\NodeTypeResolver\PHPStan\Type\TypeFactory $typeFactory)
    {
        $this->dataProviderClassMethodFactory = $dataProviderClassMethodFactory;
        $this->typeFactory = $typeFactory;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Move array argument from tests into data provider [configurable]', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

class SomeServiceTest extends TestCase
{
    public function test()
    {
        $this->doTestMultiple([1, 2, 3]);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

class SomeServiceTest extends TestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(int $number)
    {
        $this->doTestSingle($number);
    }

    public function provideData(): \Iterator
    {
        yield [1];
        yield [2];
        yield [3];
    }
}
CODE_SAMPLE
, [self::ARRAY_ARGUMENTS_TO_DATA_PROVIDERS => [new \Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider('PHPUnit\\Framework\\TestCase', 'doTestMultiple', 'doTestSingle', 'number')]])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isInTestClass($node)) {
            return null;
        }
        $this->dataProviderClassMethodRecipes = [];
        $this->traverseNodesWithCallable($node->stmts, function (\PhpParser\Node $node) {
            if (!$node instanceof \PhpParser\Node\Expr\MethodCall) {
                return null;
            }
            foreach ($this->arrayArgumentsToDataProviders as $arrayArgumentsToDataProvider) {
                $this->refactorMethodCallWithConfiguration($node, $arrayArgumentsToDataProvider);
            }
            return null;
        });
        if ($this->dataProviderClassMethodRecipes === []) {
            return null;
        }
        $dataProviderClassMethods = $this->createDataProviderClassMethodsFromRecipes();
        $node->stmts = \array_merge($node->stmts, $dataProviderClassMethods);
        return $node;
    }
    public function configure(array $arrayArgumentsToDataProviders) : void
    {
        $arrayArgumentsToDataProviders = $arrayArgumentsToDataProviders[self::ARRAY_ARGUMENTS_TO_DATA_PROVIDERS] ?? [];
        \RectorPrefix20210116\Webmozart\Assert\Assert::allIsInstanceOf($arrayArgumentsToDataProviders, \Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider::class);
        $this->arrayArgumentsToDataProviders = $arrayArgumentsToDataProviders;
    }
    private function refactorMethodCallWithConfiguration(\PhpParser\Node\Expr\MethodCall $methodCall, \Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider $arrayArgumentToDataProvider) : void
    {
        if (!$this->isMethodCallMatch($methodCall, $arrayArgumentToDataProvider)) {
            return;
        }
        if (\count($methodCall->args) !== 1) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        // resolve value types
        $firstArgumentValue = $methodCall->args[0]->value;
        if (!$firstArgumentValue instanceof \PhpParser\Node\Expr\Array_) {
            // nothing we can do
            return;
        }
        // rename method to new one handling non-array input
        $methodCall->name = new \PhpParser\Node\Identifier($arrayArgumentToDataProvider->getNewMethod());
        $dataProviderMethodName = $this->createDataProviderMethodName($methodCall);
        $this->dataProviderClassMethodRecipes[] = new \Rector\PHPUnit\ValueObject\DataProviderClassMethodRecipe($dataProviderMethodName, $methodCall->args);
        $methodCall->args = [];
        $paramAndArgs = $this->collectParamAndArgsFromArray($firstArgumentValue, $arrayArgumentToDataProvider->getVariableName());
        foreach ($paramAndArgs as $paramAndArg) {
            $methodCall->args[] = new \PhpParser\Node\Arg($paramAndArg->getVariable());
        }
        /** @var ClassMethod $classMethod */
        $classMethod = $methodCall->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NODE);
        $this->refactorTestClassMethodParams($classMethod, $paramAndArgs);
        // add data provider annotation
        $dataProviderTagNode = $this->createDataProviderTagNode($dataProviderMethodName);
        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        $phpDocInfo->addPhpDocTagNode($dataProviderTagNode);
    }
    /**
     * @return ClassMethod[]
     */
    private function createDataProviderClassMethodsFromRecipes() : array
    {
        $dataProviderClassMethods = [];
        foreach ($this->dataProviderClassMethodRecipes as $dataProviderClassMethodRecipe) {
            $dataProviderClassMethods[] = $this->dataProviderClassMethodFactory->createFromRecipe($dataProviderClassMethodRecipe);
        }
        return $dataProviderClassMethods;
    }
    private function isMethodCallMatch(\PhpParser\Node\Expr\MethodCall $methodCall, \Rector\PHPUnit\ValueObject\ArrayArgumentToDataProvider $arrayArgumentToDataProvider) : bool
    {
        if (!$this->isObjectType($methodCall->var, $arrayArgumentToDataProvider->getClass())) {
            return \false;
        }
        return $this->isName($methodCall->name, $arrayArgumentToDataProvider->getOldMethod());
    }
    private function createDataProviderMethodName(\PhpParser\Node\Expr\MethodCall $methodCall) : string
    {
        /** @var string $methodName */
        $methodName = $methodCall->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NAME);
        return 'provideDataFor' . \ucfirst($methodName);
    }
    /**
     * @return ParamAndArg[]
     */
    private function collectParamAndArgsFromArray(\PhpParser\Node\Expr\Array_ $array, string $variableName) : array
    {
        $isNestedArray = $this->isNestedArray($array);
        if ($isNestedArray) {
            return $this->collectParamAndArgsFromNestedArray($array, $variableName);
        }
        $itemsStaticType = $this->resolveItemStaticType($array, $isNestedArray);
        return $this->collectParamAndArgsFromNonNestedArray($array, $variableName, $itemsStaticType);
    }
    /**
     * @param ParamAndArg[] $paramAndArgs
     */
    private function refactorTestClassMethodParams(\PhpParser\Node\Stmt\ClassMethod $classMethod, array $paramAndArgs) : void
    {
        $classMethod->params = $this->createParams($paramAndArgs);
        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        foreach ($paramAndArgs as $paramAndArg) {
            $staticType = $paramAndArg->getType();
            if (!$staticType instanceof \PHPStan\Type\UnionType) {
                continue;
            }
            /** @var string $paramName */
            $paramName = $this->getName($paramAndArg->getVariable());
            /** @var TypeNode $staticTypeNode */
            $staticTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($staticType);
            $paramTagValueNode = $this->createParamTagNode($paramName, $staticTypeNode);
            $phpDocInfo->addTagValueNode($paramTagValueNode);
        }
    }
    private function createDataProviderTagNode(string $dataProviderMethodName) : \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode
    {
        return new \Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocTagNode('@dataProvider', new \PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode($dataProviderMethodName . '()'));
    }
    private function isNestedArray(\PhpParser\Node\Expr\Array_ $array) : bool
    {
        foreach ($array->items as $arrayItem) {
            if (!$arrayItem instanceof \PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            if ($arrayItem->value instanceof \PhpParser\Node\Expr\Array_) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @return ParamAndArg[]
     */
    private function collectParamAndArgsFromNestedArray(\PhpParser\Node\Expr\Array_ $array, string $variableName) : array
    {
        $paramAndArgs = [];
        $i = 1;
        foreach ($array->items as $arrayItem) {
            if (!$arrayItem instanceof \PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            $nestedArray = $arrayItem->value;
            if (!$nestedArray instanceof \PhpParser\Node\Expr\Array_) {
                continue;
            }
            foreach ($nestedArray->items as $nestedArrayItem) {
                if (!$nestedArrayItem instanceof \PhpParser\Node\Expr\ArrayItem) {
                    continue;
                }
                $variable = new \PhpParser\Node\Expr\Variable($variableName . ($i === 1 ? '' : $i));
                $itemsStaticType = $this->getStaticType($nestedArrayItem->value);
                $paramAndArgs[] = new \Rector\PHPUnit\ValueObject\ParamAndArg($variable, $itemsStaticType);
                ++$i;
            }
        }
        return $paramAndArgs;
    }
    private function resolveItemStaticType(\PhpParser\Node\Expr\Array_ $array, bool $isNestedArray) : \PHPStan\Type\Type
    {
        $staticTypes = [];
        if (!$isNestedArray) {
            foreach ($array->items as $arrayItem) {
                if (!$arrayItem instanceof \PhpParser\Node\Expr\ArrayItem) {
                    continue;
                }
                $staticTypes[] = $this->getStaticType($arrayItem->value);
            }
        }
        return $this->typeFactory->createMixedPassedOrUnionType($staticTypes);
    }
    /**
     * @return ParamAndArg[]
     */
    private function collectParamAndArgsFromNonNestedArray(\PhpParser\Node\Expr\Array_ $array, string $variableName, \PHPStan\Type\Type $itemsStaticType) : array
    {
        $i = 1;
        $paramAndArgs = [];
        foreach ($array->items as $arrayItem) {
            if (!$arrayItem instanceof \PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            $variable = new \PhpParser\Node\Expr\Variable($variableName . ($i === 1 ? '' : $i));
            $paramAndArgs[] = new \Rector\PHPUnit\ValueObject\ParamAndArg($variable, $itemsStaticType);
            ++$i;
            if (!$arrayItem->value instanceof \PhpParser\Node\Expr\Array_) {
                break;
            }
        }
        return $paramAndArgs;
    }
    /**
     * @param ParamAndArg[] $paramAndArgs
     * @return Param[]
     */
    private function createParams(array $paramAndArgs) : array
    {
        $params = [];
        foreach ($paramAndArgs as $paramAndArg) {
            $param = new \PhpParser\Node\Param($paramAndArg->getVariable());
            $this->setTypeIfNotNull($paramAndArg, $param);
            $params[] = $param;
        }
        return $params;
    }
    private function createParamTagNode(string $name, \PHPStan\PhpDocParser\Ast\Type\TypeNode $typeNode) : \Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode
    {
        return new \Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode($typeNode, \false, '$' . $name, '');
    }
    private function setTypeIfNotNull(\Rector\PHPUnit\ValueObject\ParamAndArg $paramAndArg, \PhpParser\Node\Param $param) : void
    {
        $staticType = $paramAndArg->getType();
        if ($staticType === null) {
            return;
        }
        if ($staticType instanceof \PHPStan\Type\UnionType) {
            return;
        }
        $phpNodeType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($staticType);
        if ($phpNodeType === null) {
            return;
        }
        $param->type = $phpNodeType;
    }
}
