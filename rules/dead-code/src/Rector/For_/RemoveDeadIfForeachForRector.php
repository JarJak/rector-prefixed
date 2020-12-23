<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\DeadCode\Rector\For_;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Scalar;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\If_;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DeadCode\Tests\Rector\For_\RemoveDeadIfForeachForRector\RemoveDeadIfForeachForRectorTest
 */
final class RemoveDeadIfForeachForRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove if, foreach and for that does not do anything', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run($someObject)
    {
        $value = 5;
        if ($value) {
        }

        if ($someObject->run()) {
        }

        foreach ($values as $value) {
        }

        return $value;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($someObject)
    {
        $value = 5;
        if ($someObject->run()) {
        }

        return $value;
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
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\If_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_::class];
    }
    /**
     * @param For_|If_|Foreach_ $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\If_) {
            $this->processIf($node);
            return null;
        }
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_) {
            $this->processForeach($node);
            return null;
        }
        // For
        if ($node->stmts !== []) {
            return null;
        }
        $this->removeNode($node);
        return null;
    }
    private function processIf(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\If_ $if) : void
    {
        if ($if->stmts !== []) {
            return;
        }
        if ($if->else !== null) {
            return;
        }
        if ($if->elseifs !== []) {
            return;
        }
        if ($this->isNodeWithSideEffect($if->cond)) {
            return;
        }
        $this->removeNode($if);
    }
    private function processForeach(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_ $foreach) : void
    {
        if ($foreach->stmts !== []) {
            return;
        }
        if ($this->isNodeWithSideEffect($foreach->expr)) {
            return;
        }
        $this->removeNode($foreach);
    }
    private function isNodeWithSideEffect(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr $expr) : bool
    {
        if ($expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable) {
            return \false;
        }
        if ($expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar) {
            return \false;
        }
        return !$this->isBool($expr);
    }
}
