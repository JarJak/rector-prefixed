<?php

declare (strict_types=1);
namespace Rector\CodeQuality\Rector\Return_;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\PhpParser\Node\AssignAndBinaryMap;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see Based on https://github.com/slevomat/coding-standard/blob/master/SlevomatCodingStandard/Sniffs/Variables/UselessVariableSniff.php
 * @see \Rector\CodeQuality\Tests\Rector\Return_\SimplifyUselessVariableRector\SimplifyUselessVariableRectorTest
 */
final class SimplifyUselessVariableRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var AssignAndBinaryMap
     */
    private $assignAndBinaryMap;
    public function __construct(\Rector\Core\PhpParser\Node\AssignAndBinaryMap $assignAndBinaryMap)
    {
        $this->assignAndBinaryMap = $assignAndBinaryMap;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Removes useless variable assigns', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
function () {
    $a = true;
    return $a;
};
CODE_SAMPLE
, <<<'CODE_SAMPLE'
function () {
    return true;
};
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Return_::class];
    }
    /**
     * @param Return_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $previousNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PREVIOUS_NODE);
        if (!$previousNode instanceof \PhpParser\Node\Stmt\Expression) {
            return null;
        }
        /** @var AssignOp|Assign $previousNode */
        $previousNode = $previousNode->expr;
        $previousVariableNode = $previousNode->var;
        // has some comment
        if ($previousVariableNode->getComments() || $previousVariableNode->getDocComment()) {
            return null;
        }
        if ($previousNode instanceof \PhpParser\Node\Expr\Assign) {
            if ($this->isReturnWithVarAnnotation($node)) {
                return null;
            }
            $node->expr = $previousNode->expr;
        }
        if ($previousNode instanceof \PhpParser\Node\Expr\AssignOp) {
            $binaryClass = $this->assignAndBinaryMap->getAlternative($previousNode);
            if ($binaryClass === null) {
                return null;
            }
            $node->expr = new $binaryClass($previousNode->var, $previousNode->expr);
        }
        $this->removeNode($previousNode);
        return $node;
    }
    private function shouldSkip(\PhpParser\Node\Stmt\Return_ $return) : bool
    {
        if (!$return->expr instanceof \PhpParser\Node\Expr\Variable) {
            return \true;
        }
        $variableNode = $return->expr;
        $previousExpression = $return->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PREVIOUS_NODE);
        if ($previousExpression === null || !$previousExpression instanceof \PhpParser\Node\Stmt\Expression) {
            return \true;
        }
        // is variable part of single assign
        $previousNode = $previousExpression->expr;
        if (!$previousNode instanceof \PhpParser\Node\Expr\AssignOp && !$previousNode instanceof \PhpParser\Node\Expr\Assign) {
            return \true;
        }
        // is the same variable
        if (!$this->areNodesEqual($previousNode->var, $variableNode)) {
            return \true;
        }
        return $this->isPreviousExpressionVisuallySimilar($previousExpression, $previousNode);
    }
    private function isReturnWithVarAnnotation(\PhpParser\Node $node) : bool
    {
        if (!$node instanceof \PhpParser\Node\Stmt\Return_) {
            return \false;
        }
        $phpDocInfo = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if (!$phpDocInfo instanceof \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo) {
            return \false;
        }
        return !$phpDocInfo->getVarType() instanceof \PHPStan\Type\MixedType;
    }
    /**
     * @param AssignOp|Assign $previousNode
     */
    private function isPreviousExpressionVisuallySimilar(\PhpParser\Node\Stmt\Expression $previousExpression, \PhpParser\Node $previousNode) : bool
    {
        $prePreviousExpression = $previousExpression->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PREVIOUS_STATEMENT);
        return $prePreviousExpression instanceof \PhpParser\Node\Stmt\Expression && $prePreviousExpression->expr instanceof \PhpParser\Node\Expr\AssignOp && $this->areNodesEqual($prePreviousExpression->expr->var, $previousNode->var);
    }
}
