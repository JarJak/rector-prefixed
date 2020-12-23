<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\CodeQuality\Rector\For_;

use _PhpScoper0a2ac50786fa\Doctrine\Inflector\Inflector;
use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Arg;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayDimFetch;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Greater;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Smaller;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\FuncCall;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PostInc;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PreInc;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Unset_;
use _PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\Manipulator\AssignManipulator;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CodeQuality\Tests\Rector\For_\ForToForeachRector\ForToForeachRectorTest
 */
final class ForToForeachRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const COUNT = 'count';
    /**
     * @var AssignManipulator
     */
    private $assignManipulator;
    /**
     * @var Inflector
     */
    private $inflector;
    /**
     * @var string|null
     */
    private $keyValueName;
    /**
     * @var string|null
     */
    private $countValueName;
    /**
     * @var Expr|null
     */
    private $countValueVariable;
    /**
     * @var Expr|null
     */
    private $iteratedExpr;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\Manipulator\AssignManipulator $assignManipulator, \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Inflector $inflector)
    {
        $this->assignManipulator = $assignManipulator;
        $this->inflector = $inflector;
    }
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change for() to foreach() where useful', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run($tokens)
    {
        for ($i = 0, $c = count($tokens); $i < $c; ++$i) {
            if ($tokens[$i][0] === T_STRING && $tokens[$i][1] === 'fn') {
                $previousNonSpaceToken = $this->getPreviousNonSpaceToken($tokens, $i);
                if ($previousNonSpaceToken !== null && $previousNonSpaceToken[0] === T_OBJECT_OPERATOR) {
                    continue;
                }
                $tokens[$i][0] = self::T_FN;
            }
        }
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($tokens)
    {
        foreach ($tokens as $i => $token) {
            if ($token[0] === T_STRING && $token[1] === 'fn') {
                $previousNonSpaceToken = $this->getPreviousNonSpaceToken($tokens, $i);
                if ($previousNonSpaceToken !== null && $previousNonSpaceToken[0] === T_OBJECT_OPERATOR) {
                    continue;
                }
                $tokens[$i][0] = self::T_FN;
            }
        }
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
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_::class];
    }
    /**
     * @param For_ $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        $this->reset();
        $this->matchInit((array) $node->init);
        if (!$this->isConditionMatch((array) $node->cond)) {
            return null;
        }
        if (!$this->isLoopMatch((array) $node->loop)) {
            return null;
        }
        if ($this->iteratedExpr === null || $this->keyValueName === null) {
            return null;
        }
        $iteratedVariable = $this->getName($this->iteratedExpr);
        if ($iteratedVariable === null) {
            return null;
        }
        $init = $node->init;
        if (\count($init) > 2) {
            return null;
        }
        if ($this->isCountValueVariableUsedInsideForStatements($node)) {
            return null;
        }
        if ($this->isAssignmentWithArrayDimFetchAsVariableInsideForStatements($node)) {
            return null;
        }
        if ($this->isArrayWithKeyValueNameUnsetted($node)) {
            return null;
        }
        $iteratedVariableSingle = $this->inflector->singularize($iteratedVariable);
        $foreach = $this->createForeach($node, $iteratedVariableSingle);
        $this->useForeachVariableInStmts($foreach->expr, $foreach->valueVar, $foreach->stmts);
        return $foreach;
    }
    private function reset() : void
    {
        $this->keyValueName = null;
        $this->countValueVariable = null;
        $this->countValueName = null;
        $this->iteratedExpr = null;
    }
    /**
     * @param Expr[] $initExprs
     */
    private function matchInit(array $initExprs) : void
    {
        foreach ($initExprs as $initExpr) {
            if (!$initExpr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign) {
                continue;
            }
            if ($this->isValue($initExpr->expr, 0)) {
                $this->keyValueName = $this->getName($initExpr->var);
            }
            if ($this->isFuncCallName($initExpr->expr, self::COUNT)) {
                $this->countValueVariable = $initExpr->var;
                $this->countValueName = $this->getName($initExpr->var);
                $this->iteratedExpr = $initExpr->expr->args[0]->value;
            }
        }
    }
    /**
     * @param Expr[] $condExprs
     */
    private function isConditionMatch(array $condExprs) : bool
    {
        if (\count($condExprs) !== 1) {
            return \false;
        }
        if ($this->keyValueName === null) {
            return \false;
        }
        if ($this->countValueName !== null) {
            return $this->isSmallerOrGreater($condExprs, $this->keyValueName, $this->countValueName);
        }
        if (!$condExprs[0] instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp) {
            return \false;
        }
        // count($values)
        if ($this->isFuncCallName($condExprs[0]->right, self::COUNT)) {
            /** @var FuncCall $countFuncCall */
            $countFuncCall = $condExprs[0]->right;
            $this->iteratedExpr = $countFuncCall->args[0]->value;
            return \true;
        }
        return \false;
    }
    /**
     * @param Expr[] $loopExprs
     * $param
     */
    private function isLoopMatch(array $loopExprs) : bool
    {
        if (\count($loopExprs) !== 1) {
            return \false;
        }
        if ($this->keyValueName === null) {
            return \false;
        }
        if ($loopExprs[0] instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PreInc || $loopExprs[0] instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PostInc) {
            return $this->isName($loopExprs[0]->var, $this->keyValueName);
        }
        return \false;
    }
    private function isCountValueVariableUsedInsideForStatements(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_ $for) : bool
    {
        return (bool) $this->betterNodeFinder->findFirst($for->stmts, function (\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool {
            return $this->areNodesEqual($this->countValueVariable, $node);
        });
    }
    private function isAssignmentWithArrayDimFetchAsVariableInsideForStatements(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_ $for) : bool
    {
        return (bool) $this->betterNodeFinder->findFirst($for->stmts, function (\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool {
            if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign) {
                return \false;
            }
            if (!$node->var instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayDimFetch) {
                return \false;
            }
            if ($this->keyValueName === null) {
                throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
            }
            return $this->isVariableName($node->var->dim, $this->keyValueName);
        });
    }
    private function isArrayWithKeyValueNameUnsetted(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_ $for) : bool
    {
        return (bool) $this->betterNodeFinder->findFirst($for->stmts, function (\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool {
            /** @var Node $parent */
            $parent = $node->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if (!$parent instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Unset_) {
                return \false;
            }
            return $node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayDimFetch;
        });
    }
    private function createForeach(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_ $for, string $iteratedVariableName) : \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_
    {
        if ($this->iteratedExpr === null) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        if ($this->keyValueName === null) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        $foreach = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_($this->iteratedExpr, new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable($iteratedVariableName));
        $foreach->stmts = $for->stmts;
        $foreach->keyVar = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable($this->keyValueName);
        return $foreach;
    }
    /**
     * @param Stmt[] $stmts
     */
    private function useForeachVariableInStmts(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr $foreachedValue, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr $singleValue, array $stmts) : void
    {
        if ($this->keyValueName === null) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        $this->traverseNodesWithCallable($stmts, function (\_PhpScoper0a2ac50786fa\PhpParser\Node $node) use($foreachedValue, $singleValue) : ?Expr {
            if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayDimFetch) {
                return null;
            }
            // must be the same as foreach value
            if (!$this->areNodesEqual($node->var, $foreachedValue)) {
                return null;
            }
            $parentNode = $node->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($this->assignManipulator->isNodePartOfAssign($parentNode)) {
                return null;
            }
            if ($this->isArgParentCount($parentNode)) {
                return null;
            }
            // is dim same as key value name, ...[$i]
            if ($this->keyValueName === null) {
                throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
            }
            if (!$this->isVariableName($node->dim, $this->keyValueName)) {
                return null;
            }
            return $singleValue;
        });
    }
    /**
     * @param Expr[] $condExprs
     */
    private function isSmallerOrGreater(array $condExprs, string $keyValueName, string $countValueName) : bool
    {
        // $i < $count
        if ($condExprs[0] instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Smaller) {
            if (!$this->isName($condExprs[0]->left, $keyValueName)) {
                return \false;
            }
            return $this->isName($condExprs[0]->right, $countValueName);
        }
        // $i > $count
        if ($condExprs[0] instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Greater) {
            if (!$this->isName($condExprs[0]->left, $countValueName)) {
                return \false;
            }
            return $this->isName($condExprs[0]->right, $keyValueName);
        }
        return \false;
    }
    private function isArgParentCount(?\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool
    {
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Arg) {
            /** @var Node $parentNode */
            $parentNode = $node->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($this->isFuncCallName($parentNode, self::COUNT)) {
                return \true;
            }
        }
        return \false;
    }
}
