<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\DeadCode\Rector\Plus;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Div as AssignDiv;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Minus as AssignMinus;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Mul as AssignMul;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Div;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Minus;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Mul;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Plus;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\UnaryMinus;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://3v4l.org/I0BGs
 *
 * @see \Rector\DeadCode\Tests\Rector\Plus\RemoveDeadZeroAndOneOperationRector\RemoveDeadZeroAndOneOperationRectorTest
 */
final class RemoveDeadZeroAndOneOperationRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove operation with 1 and 0, that have no effect on the value', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $value = 5 * 1;
        $value = 5 + 0;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $value = 5;
        $value = 5;
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
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Plus::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Minus::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Mul::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Div::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Plus::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Minus::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Mul::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Div::class];
    }
    /**
     * @param Plus|Minus|Mul|Div|AssignPlus|AssignMinus|AssignMul|AssignDiv $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        $changedNode = null;
        $previousNode = $node;
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp) {
            $changedNode = $this->processAssignOp($node);
        }
        // -, +
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp) {
            $changedNode = $this->processBinaryOp($node);
        }
        // recurse nested combinations
        while ($changedNode !== null && !$this->areNodesEqual($previousNode, $changedNode)) {
            $previousNode = $changedNode;
            if ($changedNode instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp || $changedNode instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp) {
                $changedNode = $this->refactor($changedNode);
            }
            // nothing more to change, return last node
            if ($changedNode === null) {
                return $previousNode;
            }
        }
        return $changedNode;
    }
    private function processAssignOp(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr
    {
        // +=, -=
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Plus || $node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Minus) {
            if (!$this->isValue($node->expr, 0)) {
                return null;
            }
            if ($this->isNumberType($node->var)) {
                return $node->var;
            }
        }
        // *, /
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Mul || $node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\AssignOp\Div) {
            if (!$this->isValue($node->expr, 1)) {
                return null;
            }
            if ($this->isNumberType($node->var)) {
                return $node->var;
            }
        }
        return null;
    }
    private function processBinaryOp(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr
    {
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Plus || $node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Minus) {
            return $this->processBinaryPlusAndMinus($node);
        }
        // *, /
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Mul || $node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Div) {
            return $this->processBinaryMulAndDiv($node);
        }
        return null;
    }
    /**
     * @param Plus|Minus $binaryOp
     */
    private function processBinaryPlusAndMinus(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp $binaryOp) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr
    {
        if ($this->isValue($binaryOp->left, 0) && $this->isNumberType($binaryOp->right)) {
            if ($binaryOp instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Minus) {
                return new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\UnaryMinus($binaryOp->right);
            }
            return $binaryOp->right;
        }
        if ($this->isValue($binaryOp->right, 0) && $this->isNumberType($binaryOp->left)) {
            return $binaryOp->left;
        }
        return null;
    }
    /**
     * @param Mul|Div $binaryOp
     */
    private function processBinaryMulAndDiv(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp $binaryOp) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr
    {
        if ($binaryOp instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Mul && $this->isValue($binaryOp->left, 1) && $this->isNumberType($binaryOp->right)) {
            return $binaryOp->right;
        }
        if ($this->isValue($binaryOp->right, 1) && $this->isNumberType($binaryOp->left)) {
            return $binaryOp->left;
        }
        return null;
    }
}
