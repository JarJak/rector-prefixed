<?php

declare (strict_types=1);
namespace Rector\NodeRemoval;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\While_;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;
final class BreakingRemovalGuard
{
    public function ensureNodeCanBeRemove(\PhpParser\Node $node) : void
    {
        if ($this->isLegalNodeRemoval($node)) {
            return;
        }
        // validate the $parentNodenode can be removed
        $parentNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        throw new \Rector\Core\Exception\ShouldNotHappenException(\sprintf(
            'Node "%s" is child of "%s", so it cannot be removed as it would break PHP code. Change or remove the parent node instead.',
            \get_class($node),
            /** @var Node $parentNode */
            \get_class($parentNode)
        ));
    }
    public function isLegalNodeRemoval(\PhpParser\Node $node) : bool
    {
        $parent = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parent instanceof \PhpParser\Node\Stmt\If_ && $parent->cond === $node) {
            return \false;
        }
        if ($parent instanceof \PhpParser\Node\Expr\BooleanNot) {
            $parent = $parent->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        }
        return !$parent instanceof \PhpParser\Node\Expr\Assign && !$this->isIfCondition($node) && !$this->isWhileCondition($node);
    }
    private function isIfCondition(\PhpParser\Node $node) : bool
    {
        $parentNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$parentNode instanceof \PhpParser\Node\Stmt\If_) {
            return \false;
        }
        return $parentNode->cond === $node;
    }
    private function isWhileCondition(\PhpParser\Node $node) : bool
    {
        $parentNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$parentNode instanceof \PhpParser\Node\Stmt\While_) {
            return \false;
        }
        return $parentNode->cond === $node;
    }
}
