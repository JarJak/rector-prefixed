<?php

declare (strict_types=1);
namespace Rector\Core\PhpParser\Node\Manipulator;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
final class ForeachManipulator
{
    public function matchOnlyStmt(\PhpParser\Node\Stmt\Foreach_ $foreach, callable $callable) : ?\PhpParser\Node
    {
        if (\count($foreach->stmts) !== 1) {
            return null;
        }
        $innerNode = $foreach->stmts[0];
        $innerNode = $innerNode instanceof \PhpParser\Node\Stmt\Expression ? $innerNode->expr : $innerNode;
        return $callable($innerNode, $foreach);
    }
}
