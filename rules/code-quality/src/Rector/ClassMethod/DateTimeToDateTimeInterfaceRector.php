<?php

declare (strict_types=1);
namespace Rector\CodeQuality\Rector\ClassMethod;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CodeQuality\Tests\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector\DateTimeToDateTimeInterfaceRectorTest
 */
final class DateTimeToDateTimeInterfaceRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string[]
     */
    private const METHODS_RETURNING_CLASS_INSTANCE_MAP = ['add', 'modify', \Rector\Core\ValueObject\MethodName::SET_STATE, 'setDate', 'setISODate', 'setTime', 'setTimestamp', 'setTimezone', 'sub'];
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    public function __construct(\Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver)
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes DateTime type-hint to DateTimeInterface', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass {
    public function methodWithDateTime(\DateTime $dateTime)
    {
        return true;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass {
    /**
     * @param \DateTime|\DateTimeImmutable $dateTime
     */
    public function methodWithDateTime(\DateTimeInterface $dateTime)
    {
        return true;
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isAtLeastPhpVersion(\Rector\Core\ValueObject\PhpVersionFeature::DATE_TIME_INTERFACE)) {
            return null;
        }
        $isModifiedNode = \false;
        foreach ($node->getParams() as $param) {
            if (!$this->isDateTimeParam($param)) {
                continue;
            }
            $this->refactorParamTypeHint($param);
            $this->refactorParamDocBlock($param, $node);
            $this->refactorMethodCalls($param, $node);
            $isModifiedNode = \true;
        }
        if (!$isModifiedNode) {
            return null;
        }
        return $node;
    }
    private function isDateTimeParam(\PhpParser\Node\Param $param) : bool
    {
        return $this->nodeTypeResolver->isObjectTypeOrNullableObjectType($param, \DateTime::class);
    }
    private function refactorParamTypeHint(\PhpParser\Node\Param $param) : void
    {
        $fullyQualified = new \PhpParser\Node\Name\FullyQualified(\DateTimeInterface::class);
        if ($param->type instanceof \PhpParser\Node\NullableType) {
            $param->type = new \PhpParser\Node\NullableType($fullyQualified);
            return;
        }
        $param->type = $fullyQualified;
    }
    private function refactorParamDocBlock(\PhpParser\Node\Param $param, \PhpParser\Node\Stmt\ClassMethod $classMethod) : void
    {
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            $phpDocInfo = $this->phpDocInfoFactory->createEmpty($classMethod);
        }
        $types = [new \PHPStan\Type\ObjectType(\DateTime::class), new \PHPStan\Type\ObjectType(\DateTimeImmutable::class)];
        if ($param->type instanceof \PhpParser\Node\NullableType) {
            $types[] = new \PHPStan\Type\NullType();
        }
        $paramName = $this->getName($param->var);
        if ($paramName === null) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        $phpDocInfo->changeParamType(new \PHPStan\Type\UnionType($types), $param, $paramName);
    }
    private function refactorMethodCalls(\PhpParser\Node\Param $param, \PhpParser\Node\Stmt\ClassMethod $classMethod) : void
    {
        if ($classMethod->stmts === null) {
            return;
        }
        $this->traverseNodesWithCallable($classMethod->stmts, function (\PhpParser\Node $node) use($param) : void {
            if (!$node instanceof \PhpParser\Node\Expr\MethodCall) {
                return;
            }
            $this->refactorMethodCall($param, $node);
        });
    }
    private function refactorMethodCall(\PhpParser\Node\Param $param, \PhpParser\Node\Expr\MethodCall $methodCall) : void
    {
        $paramName = $this->getName($param->var);
        if ($paramName === null) {
            return;
        }
        if ($this->shouldSkipMethodCallRefactor($paramName, $methodCall)) {
            return;
        }
        $assign = new \PhpParser\Node\Expr\Assign(new \PhpParser\Node\Expr\Variable($paramName), $methodCall);
        /** @var Node $parent */
        $parent = $methodCall->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parent instanceof \PhpParser\Node\Arg) {
            $parent->value = $assign;
            return;
        }
        if (!$parent instanceof \PhpParser\Node\Stmt\Expression) {
            return;
        }
        $parent->expr = $assign;
    }
    private function shouldSkipMethodCallRefactor(string $paramName, \PhpParser\Node\Expr\MethodCall $methodCall) : bool
    {
        if (!$this->isName($methodCall->var, $paramName)) {
            return \true;
        }
        if (!$this->isNames($methodCall->name, self::METHODS_RETURNING_CLASS_INSTANCE_MAP)) {
            return \true;
        }
        $parentNode = $methodCall->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parentNode === null) {
            return \true;
        }
        return $parentNode instanceof \PhpParser\Node\Expr\Assign;
    }
}
