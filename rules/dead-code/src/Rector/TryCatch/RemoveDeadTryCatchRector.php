<?php

declare (strict_types=1);
namespace Rector\DeadCode\Rector\TryCatch;

use PhpParser\Node;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\Node\Stmt\TryCatch;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DeadCode\Tests\Rector\TryCatch\RemoveDeadTryCatchRector\RemoveDeadTryCatchRectorTest
 */
final class RemoveDeadTryCatchRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove dead try/catch', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        try {
            // some code
        }
        catch (Throwable $throwable) {
            throw $throwable;
        }
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        // some code
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
        return [\PhpParser\Node\Stmt\TryCatch::class];
    }
    /**
     * @param TryCatch $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (\count($node->catches) !== 1) {
            return null;
        }
        /** @var Catch_ $onlyCatch */
        $onlyCatch = $node->catches[0];
        if (\count($onlyCatch->stmts) !== 1) {
            return null;
        }
        if ($node->finally !== null && $node->finally->stmts !== []) {
            return null;
        }
        $onlyCatchStmt = $onlyCatch->stmts[0];
        if (!$onlyCatchStmt instanceof \PhpParser\Node\Stmt\Throw_) {
            return null;
        }
        if (!$this->areNodesEqual($onlyCatch->var, $onlyCatchStmt->expr)) {
            return null;
        }
        $this->addNodesAfterNode((array) $node->stmts, $node);
        $this->removeNode($node);
        return null;
    }
}