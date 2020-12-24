<?php

declare (strict_types=1);
namespace _PhpScoper2a4e7ab1ecbc\Rector\Core\PhpParser\Node\Manipulator;

use _PhpScoper2a4e7ab1ecbc\PhpParser\Node;
use _PhpScoper2a4e7ab1ecbc\PhpParser\Node\Expr\Assign;
use _PhpScoper2a4e7ab1ecbc\PhpParser\Node\Expr\MethodCall;
use _PhpScoper2a4e7ab1ecbc\PhpParser\Node\Expr\StaticCall;
use _PhpScoper2a4e7ab1ecbc\PhpParser\Node\Param;
use _PhpScoper2a4e7ab1ecbc\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoper2a4e7ab1ecbc\PhpParser\NodeTraverser;
use _PhpScoper2a4e7ab1ecbc\Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser;
use _PhpScoper2a4e7ab1ecbc\Rector\NodeNameResolver\NodeNameResolver;
final class ClassMethodPropertyFetchManipulator
{
    /**
     * @var CallableNodeTraverser
     */
    private $callableNodeTraverser;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    public function __construct(\_PhpScoper2a4e7ab1ecbc\Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser $callableNodeTraverser, \_PhpScoper2a4e7ab1ecbc\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver)
    {
        $this->callableNodeTraverser = $callableNodeTraverser;
        $this->nodeNameResolver = $nodeNameResolver;
    }
    /**
     * In case the property name is different to param name:
     *
     * E.g.:
     * (SomeType $anotherValue)
     * $this->value = $anotherValue;
     * ↓
     * (SomeType $anotherValue)
     */
    public function resolveParamForPropertyFetch(\_PhpScoper2a4e7ab1ecbc\PhpParser\Node\Stmt\ClassMethod $classMethod, string $propertyName) : ?\_PhpScoper2a4e7ab1ecbc\PhpParser\Node\Param
    {
        $assignedParamName = null;
        $this->callableNodeTraverser->traverseNodesWithCallable((array) $classMethod->stmts, function (\_PhpScoper2a4e7ab1ecbc\PhpParser\Node $node) use($propertyName, &$assignedParamName) : ?int {
            if (!$node instanceof \_PhpScoper2a4e7ab1ecbc\PhpParser\Node\Expr\Assign) {
                return null;
            }
            if (!$this->nodeNameResolver->isName($node->var, $propertyName)) {
                return null;
            }
            if ($node->expr instanceof \_PhpScoper2a4e7ab1ecbc\PhpParser\Node\Expr\MethodCall) {
                return null;
            }
            if ($node->expr instanceof \_PhpScoper2a4e7ab1ecbc\PhpParser\Node\Expr\StaticCall) {
                return null;
            }
            $assignedParamName = $this->nodeNameResolver->getName($node->expr);
            return \_PhpScoper2a4e7ab1ecbc\PhpParser\NodeTraverser::STOP_TRAVERSAL;
        });
        /** @var string|null $assignedParamName */
        if ($assignedParamName === null) {
            return null;
        }
        /** @var Param $param */
        foreach ((array) $classMethod->params as $param) {
            if (!$this->nodeNameResolver->isName($param, $assignedParamName)) {
                continue;
            }
            return $param;
        }
        return null;
    }
}
