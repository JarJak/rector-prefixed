<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\CodingStyle\Rector\Switch_;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BooleanOr;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\Equal;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Break_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Case_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Else_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ElseIf_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\If_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Switch_;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CodingStyle\Tests\Rector\Switch_\BinarySwitchToIfElseRector\BinarySwitchToIfElseRectorTest
 */
final class BinarySwitchToIfElseRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes switch with 2 options to if-else', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
switch ($foo) {
    case 'my string':
        $result = 'ok';
    break;

    default:
        $result = 'not ok';
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
if ($foo == 'my string') {
    $result = 'ok;
} else {
    $result = 'not ok';
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Switch_::class];
    }
    /**
     * @param Switch_ $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if (\count((array) $node->cases) > 2) {
            return null;
        }
        /** @var Case_ $firstCase */
        $firstCase = \array_shift($node->cases);
        if ($firstCase->cond === null) {
            return null;
        }
        /** @var Case_|null $secondCase */
        $secondCase = \array_shift($node->cases);
        // special case with empty first case → ||
        $isFirstCaseEmpty = $firstCase->stmts === [];
        if ($isFirstCaseEmpty && $secondCase !== null && $secondCase->cond !== null) {
            $else = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BooleanOr(new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\Equal($node->cond, $firstCase->cond), new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\Equal($node->cond, $secondCase->cond));
            $ifNode = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\If_($else);
            $ifNode->stmts = $this->removeBreakNodes($secondCase->stmts);
            return $ifNode;
        }
        $ifNode = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\If_(new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\Equal($node->cond, $firstCase->cond));
        $ifNode->stmts = $this->removeBreakNodes($firstCase->stmts);
        // just one condition
        if ($secondCase === null) {
            return $ifNode;
        }
        if ($secondCase->cond !== null) {
            // has condition
            $equal = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\Equal($node->cond, $secondCase->cond);
            $ifNode->elseifs[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ElseIf_($equal, $this->removeBreakNodes($secondCase->stmts));
        } else {
            // defaults
            $ifNode->else = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Else_($this->removeBreakNodes($secondCase->stmts));
        }
        return $ifNode;
    }
    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    private function removeBreakNodes(array $stmts) : array
    {
        foreach ($stmts as $key => $node) {
            if ($node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Break_) {
                unset($stmts[$key]);
            }
        }
        return $stmts;
    }
}
