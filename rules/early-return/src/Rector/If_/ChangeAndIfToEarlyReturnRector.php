<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\EarlyReturn\Rector\If_;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use _PhpScopere8e811afab72\PhpParser\Node\FunctionLike;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Continue_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Else_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ElseIf_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\For_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Foreach_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\If_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\While_;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\IfManipulator;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\StmtsManipulator;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\EarlyReturn\NodeTransformer\ConditionInverter;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\EarlyReturn\Tests\Rector\If_\ChangeAndIfToEarlyReturnRector\ChangeAndIfToEarlyReturnRectorTest
 */
final class ChangeAndIfToEarlyReturnRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string[]
     */
    public const LOOP_TYPES = [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Foreach_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\For_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\While_::class];
    /**
     * @var IfManipulator
     */
    private $ifManipulator;
    /**
     * @var ConditionInverter
     */
    private $conditionInverter;
    /**
     * @var StmtsManipulator
     */
    private $stmtsManipulator;
    public function __construct(\_PhpScopere8e811afab72\Rector\EarlyReturn\NodeTransformer\ConditionInverter $conditionInverter, \_PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\IfManipulator $ifManipulator, \_PhpScopere8e811afab72\Rector\Core\PhpParser\Node\Manipulator\StmtsManipulator $stmtsManipulator)
    {
        $this->ifManipulator = $ifManipulator;
        $this->conditionInverter = $conditionInverter;
        $this->stmtsManipulator = $stmtsManipulator;
    }
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes if && to early return', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function canDrive(Car $car)
    {
        if ($car->hasWheels && $car->hasFuel) {
            return true;
        }

        return false;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function canDrive(Car $car)
    {
        if (!$car->hasWheels) {
            return false;
        }

        if (!$car->hasFuel) {
            return false;
        }

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
        return [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_::class];
    }
    /**
     * @param If_ $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $ifReturn = $this->getIfReturn($node);
        if ($ifReturn === null) {
            return null;
        }
        /** @var BooleanAnd $expr */
        $expr = $node->cond;
        $conditions = $this->getBooleanAndConditions($expr);
        $ifs = $this->createInvertedIfNodesFromConditions($node, $conditions);
        $this->mirrorComments($ifs[0], $node);
        $this->addNodesAfterNode($ifs, $node);
        $this->addNodeAfterNode($ifReturn, $node);
        $ifNextReturn = $this->getIfNextReturn($node);
        if ($ifNextReturn !== null && !$this->isIfInLoop($node)) {
            $this->removeNode($ifNextReturn);
        }
        $this->removeNode($node);
        return null;
    }
    private function shouldSkip(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : bool
    {
        if (!$this->ifManipulator->isIfWithOnlyOneStmt($if)) {
            return \true;
        }
        if ($this->isIfReturnsVoid($if)) {
            return \true;
        }
        if ($this->isParentIfReturnsVoid($if)) {
            return \true;
        }
        if (!$if->cond instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\BooleanAnd) {
            return \true;
        }
        if (!$this->isFunctionLikeReturnsVoid($if)) {
            return \true;
        }
        if ($if->else !== null) {
            return \true;
        }
        if ($if->elseifs !== []) {
            return \true;
        }
        if ($this->isNestedIfInLoop($if)) {
            return \true;
        }
        return !$this->isLastIfOrBeforeLastReturn($if);
    }
    private function getIfReturn(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : ?\_PhpScopere8e811afab72\PhpParser\Node\Stmt
    {
        $ifStmt = \end($if->stmts);
        if ($ifStmt === \false) {
            return null;
        }
        return $ifStmt;
    }
    /**
     * @return Expr[]
     */
    private function getBooleanAndConditions(\_PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\BooleanAnd $booleanAnd) : array
    {
        $ifs = [];
        while (\property_exists($booleanAnd, 'left')) {
            $ifs[] = $booleanAnd->right;
            $booleanAnd = $booleanAnd->left;
            if (!$booleanAnd instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\BooleanAnd) {
                $ifs[] = $booleanAnd;
                break;
            }
        }
        \krsort($ifs);
        return $ifs;
    }
    /**
     * @param Expr[] $conditions
     * @return If_[]
     */
    private function createInvertedIfNodesFromConditions(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $node, array $conditions) : array
    {
        $isIfInLoop = $this->isIfInLoop($node);
        $ifs = [];
        foreach ($conditions as $condition) {
            $invertedCondition = $this->conditionInverter->createInvertedCondition($condition);
            $if = new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_($invertedCondition);
            if ($isIfInLoop && $this->getIfNextReturn($node) === null) {
                $if->stmts = [new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Continue_()];
            } else {
                $if->stmts = [new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_()];
            }
            $ifs[] = $if;
        }
        return $ifs;
    }
    private function getIfNextReturn(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : ?\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_
    {
        $nextNode = $if->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        if (!$nextNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_) {
            return null;
        }
        return $nextNode;
    }
    private function isIfInLoop(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : bool
    {
        $parentLoop = $this->betterNodeFinder->findFirstParentInstanceOf($if, self::LOOP_TYPES);
        return $parentLoop !== null;
    }
    private function isIfReturnsVoid(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : bool
    {
        $lastStmt = $this->stmtsManipulator->getUnwrappedLastStmt($if->stmts);
        if (!$lastStmt instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_) {
            return \false;
        }
        return $lastStmt->expr === null;
    }
    private function isParentIfReturnsVoid(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : bool
    {
        $parentNode = $if->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$parentNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_) {
            return \false;
        }
        return $this->isIfReturnsVoid($parentNode);
    }
    private function isFunctionLikeReturnsVoid(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : bool
    {
        /** @var FunctionLike|null $functionLike */
        $functionLike = $this->betterNodeFinder->findFirstParentInstanceOf($if, \_PhpScopere8e811afab72\PhpParser\Node\FunctionLike::class);
        if ($functionLike === null) {
            return \true;
        }
        if ($functionLike->getStmts() === null) {
            return \true;
        }
        $returns = $this->betterNodeFinder->findInstanceOf($functionLike->getStmts(), \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_::class);
        if ($returns === []) {
            return \true;
        }
        $nonVoidReturns = \array_filter($returns, function (\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_ $return) : bool {
            return $return->expr !== null;
        });
        return $nonVoidReturns === [];
    }
    private function isNestedIfInLoop(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : bool
    {
        if (!$this->isIfInLoop($if)) {
            return \false;
        }
        return (bool) $this->betterNodeFinder->findFirstParentInstanceOf($if, [\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Else_::class, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ElseIf_::class]);
    }
    private function isLastIfOrBeforeLastReturn(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\If_ $if) : bool
    {
        $nextNode = $if->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        if ($nextNode === null) {
            return \true;
        }
        return $nextNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Return_;
    }
}