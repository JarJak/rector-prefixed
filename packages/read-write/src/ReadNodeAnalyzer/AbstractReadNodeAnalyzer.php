<?php

declare (strict_types=1);
namespace Rector\ReadWrite\ReadNodeAnalyzer;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\Core\NodeFinder\NodeUsageFinder;
use Rector\NodeNestingScope\ParentScopeFinder;
use Rector\NodeTypeResolver\Node\AttributeKey;
abstract class AbstractReadNodeAnalyzer
{
    /**
     * @var ParentScopeFinder
     */
    protected $parentScopeFinder;
    /**
     * @var NodeUsageFinder
     */
    protected $nodeUsageFinder;
    /**
     * @required
     */
    public function autowireAbstractReadNodeAnalyzer(\Rector\NodeNestingScope\ParentScopeFinder $parentScopeFinder, \Rector\Core\NodeFinder\NodeUsageFinder $nodeUsageFinder) : void
    {
        $this->parentScopeFinder = $parentScopeFinder;
        $this->nodeUsageFinder = $nodeUsageFinder;
    }
    protected function isCurrentContextRead(\PhpParser\Node\Expr $expr) : bool
    {
        $parent = $expr->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parent instanceof \PhpParser\Node\Stmt\Return_) {
            return \true;
        }
        if ($parent instanceof \PhpParser\Node\Arg) {
            return \true;
        }
        if ($parent instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            $parentParent = $parent->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if (!$parentParent instanceof \PhpParser\Node\Expr\Assign) {
                return \true;
            }
            return $parentParent->var !== $parent;
        }
        throw new \Rector\Core\Exception\NotImplementedYetException();
    }
}
