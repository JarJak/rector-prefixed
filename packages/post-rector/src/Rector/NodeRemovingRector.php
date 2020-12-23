<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\PostRector\Rector;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall;
use _PhpScoper0a2ac50786fa\PhpParser\NodeTraverser;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\NodeFactory;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Rector\PostRector\Collector\NodesToRemoveCollector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
final class NodeRemovingRector extends \_PhpScoper0a2ac50786fa\Rector\PostRector\Rector\AbstractPostRector
{
    /**
     * @var NodesToRemoveCollector
     */
    private $nodesToRemoveCollector;
    /**
     * @var NodeFactory
     */
    private $nodeFactory;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\NodeFactory $nodeFactory, \_PhpScoper0a2ac50786fa\Rector\PostRector\Collector\NodesToRemoveCollector $nodesToRemoveCollector)
    {
        $this->nodesToRemoveCollector = $nodesToRemoveCollector;
        $this->nodeFactory = $nodeFactory;
    }
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('PostRector that removes nodes', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$value = 1000;
$string = new String_(...);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$value = 1000;
CODE_SAMPLE
)]);
    }
    public function getPriority() : int
    {
        return 800;
    }
    public function enterNode(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        if (!$this->nodesToRemoveCollector->isActive()) {
            return null;
        }
        // special case for fluent methods
        foreach ($this->nodesToRemoveCollector->getNodesToRemove() as $key => $nodeToRemove) {
            if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall) {
                continue;
            }
            if (!$nodeToRemove instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall) {
                continue;
            }
            // replace chain method call by non-chain method call
            if (!$this->isChainMethodCallNodeToBeRemoved($node, $nodeToRemove)) {
                continue;
            }
            $this->nodesToRemoveCollector->unset($key);
            $methodName = $this->getName($node->name);
            /** @var MethodCall $nestedMethodCall */
            $nestedMethodCall = $node->var;
            /** @var string $methodName */
            return $this->nodeFactory->createMethodCall($nestedMethodCall->var, $methodName, $node->args);
        }
        if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp) {
            return null;
        }
        return $this->removePartOfBinaryOp($node);
    }
    /**
     * @return int|Node
     */
    public function leaveNode(\_PhpScoper0a2ac50786fa\PhpParser\Node $node)
    {
        foreach ($this->nodesToRemoveCollector->getNodesToRemove() as $key => $nodeToRemove) {
            $nodeToRemoveParent = $nodeToRemove->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($nodeToRemoveParent instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp) {
                continue;
            }
            if ($node === $nodeToRemove) {
                $this->nodesToRemoveCollector->unset($key);
                return \_PhpScoper0a2ac50786fa\PhpParser\NodeTraverser::REMOVE_NODE;
            }
        }
        return $node;
    }
    private function isChainMethodCallNodeToBeRemoved(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall $mainMethodCall, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall $toBeRemovedMethodCall) : bool
    {
        if (!$mainMethodCall instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall || !$mainMethodCall->var instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall) {
            return \false;
        }
        if ($toBeRemovedMethodCall !== $mainMethodCall->var) {
            return \false;
        }
        $methodName = $this->getName($mainMethodCall->name);
        return $methodName !== null;
    }
    private function removePartOfBinaryOp(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp $binaryOp) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        // handle left/right binary remove, e.g. "true && false" → remove false → "true"
        foreach ($this->nodesToRemoveCollector->getNodesToRemove() as $key => $nodeToRemove) {
            // remove node
            $nodeToRemoveParentNode = $nodeToRemove->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if (!$nodeToRemoveParentNode instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp) {
                continue;
            }
            if ($binaryOp->left === $nodeToRemove) {
                $this->nodesToRemoveCollector->unset($key);
                return $binaryOp->right;
            }
            if ($binaryOp->right === $nodeToRemove) {
                $this->nodesToRemoveCollector->unset($key);
                return $binaryOp->left;
            }
        }
        return null;
    }
}
