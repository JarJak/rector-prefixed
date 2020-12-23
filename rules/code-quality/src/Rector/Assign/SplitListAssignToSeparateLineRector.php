<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\CodeQuality\Rector\Assign;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\List_;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://mobile.twitter.com/ivanhoe011/status/1246376872931401728
 *
 * @see \Rector\CodeQuality\Tests\Rector\Assign\SplitListAssignToSeparateLineRector\SplitListAssignToSeparateLineRectorTest
 */
final class SplitListAssignToSeparateLineRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Splits `[$a, $b] = [5, 10]` scalar assign to standalone lines', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(): void
    {
        [$a, $b] = [1, 2];
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(): void
    {
        $a = 1;
        $b = 2;
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
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign::class];
    }
    /**
     * @param Assign $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        /** @var Array_|List_ $leftArray */
        $leftArray = $node->var;
        /** @var Array_ $rightArray */
        $rightArray = $node->expr;
        $standaloneAssigns = $this->createStandaloneAssigns($leftArray, $rightArray);
        $this->addNodesAfterNode($standaloneAssigns, $node);
        $this->removeNode($node);
        return $node;
    }
    private function shouldSkip(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign $assign) : bool
    {
        if (!$assign->var instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_ && !$assign->var instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\List_) {
            return \true;
        }
        if (!$assign->expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_) {
            return \true;
        }
        if (\count((array) $assign->var->items) !== \count((array) $assign->expr->items)) {
            return \true;
        }
        // is value swap
        return $this->isValueSwap($assign->var, $assign->expr);
    }
    /**
     * @param Array_|List_ $node
     * @return Assign[]
     */
    private function createStandaloneAssigns(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_ $rightArray) : array
    {
        $standaloneAssigns = [];
        foreach ($node->items as $key => $leftArrayItem) {
            if ($leftArrayItem === null) {
                continue;
            }
            $rightArrayItem = $rightArray->items[$key];
            if ($rightArrayItem === null) {
                continue;
            }
            $standaloneAssigns[] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign($leftArrayItem->value, $rightArrayItem);
        }
        return $standaloneAssigns;
    }
    /**
     * @param Array_|List_ $firstArray
     * @param Array_|List_ $secondArray
     */
    private function isValueSwap($firstArray, $secondArray) : bool
    {
        $firstArrayItemsHash = $this->getArrayItemsHash($firstArray);
        $secondArrayItemsHash = $this->getArrayItemsHash($secondArray);
        return $firstArrayItemsHash === $secondArrayItemsHash;
    }
    /**
     * @param Array_|List_ $node
     */
    private function getArrayItemsHash(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : string
    {
        $arrayItemsHashes = [];
        foreach ($node->items as $arrayItem) {
            $arrayItemsHashes[] = $this->printWithoutComments($arrayItem);
        }
        \sort($arrayItemsHashes);
        $arrayItemsHash = \implode('', $arrayItemsHashes);
        return \sha1($arrayItemsHash);
    }
}
