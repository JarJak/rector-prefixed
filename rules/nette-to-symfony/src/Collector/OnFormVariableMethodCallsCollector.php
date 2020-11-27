<?php

declare (strict_types=1);
namespace Rector\NetteToSymfony\Collector;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeTraverser;
use Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\NodeTypeResolver\NodeTypeResolver;
final class OnFormVariableMethodCallsCollector
{
    /**
     * @var CallableNodeTraverser
     */
    private $callableNodeTraverser;
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    /**
     * @var BetterStandardPrinter
     */
    private $betterStandardPrinter;
    public function __construct(\Rector\Core\PhpParser\Printer\BetterStandardPrinter $betterStandardPrinter, \Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser $callableNodeTraverser, \Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver)
    {
        $this->callableNodeTraverser = $callableNodeTraverser;
        $this->nodeTypeResolver = $nodeTypeResolver;
        $this->betterStandardPrinter = $betterStandardPrinter;
    }
    /**
     * @return MethodCall[]
     */
    public function collectFromClassMethod(\PhpParser\Node\Stmt\ClassMethod $classMethod) : array
    {
        $newFormVariable = $this->resolveNewFormVariable($classMethod);
        if ($newFormVariable === null) {
            return [];
        }
        return $this->collectOnFormVariableMethodCalls($classMethod, $newFormVariable);
    }
    /**
     * Matches:
     * $form = new Form;
     */
    private function resolveNewFormVariable(\PhpParser\Node\Stmt\ClassMethod $classMethod) : ?\PhpParser\Node\Expr
    {
        $newFormVariable = null;
        $this->callableNodeTraverser->traverseNodesWithCallable((array) $classMethod->getStmts(), function (\PhpParser\Node $node) use(&$newFormVariable) : ?int {
            if (!$node instanceof \PhpParser\Node\Expr\Assign) {
                return null;
            }
            if (!$this->nodeTypeResolver->isObjectType($node->expr, '_PhpScoper26e51eeacccf\\Nette\\Application\\UI\\Form')) {
                return null;
            }
            $newFormVariable = $node->var;
            return \PhpParser\NodeTraverser::STOP_TRAVERSAL;
        });
        return $newFormVariable;
    }
    /**
     * @return MethodCall[]
     */
    private function collectOnFormVariableMethodCalls(\PhpParser\Node\Stmt\ClassMethod $classMethod, \PhpParser\Node\Expr $expr) : array
    {
        $onFormVariableMethodCalls = [];
        $this->callableNodeTraverser->traverseNodesWithCallable((array) $classMethod->getStmts(), function (\PhpParser\Node $node) use($expr, &$onFormVariableMethodCalls) {
            if (!$node instanceof \PhpParser\Node\Expr\MethodCall) {
                return null;
            }
            if (!$this->betterStandardPrinter->areNodesEqual($node->var, $expr)) {
                return null;
            }
            $onFormVariableMethodCalls[] = $node;
            return null;
        });
        return $onFormVariableMethodCalls;
    }
}
