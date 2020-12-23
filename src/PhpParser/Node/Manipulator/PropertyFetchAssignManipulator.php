<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\Manipulator;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PropertyFetch;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser;
use _PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver;
final class PropertyFetchAssignManipulator
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var CallableNodeTraverser
     */
    private $callableNodeTraverser;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser $callableNodeTraverser, \_PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->callableNodeTraverser = $callableNodeTraverser;
    }
    /**
     * @return string[]
     */
    public function getPropertyNamesOfAssignOfVariable(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, string $paramName) : array
    {
        $propertyNames = [];
        $this->callableNodeTraverser->traverseNodesWithCallable($node, function (\_PhpScoper0a2ac50786fa\PhpParser\Node $node) use($paramName, &$propertyNames) {
            if (!$node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign) {
                return null;
            }
            if (!$this->isVariableAssignToThisPropertyFetch($node, $paramName)) {
                return null;
            }
            /** @var Assign $node */
            $propertyName = $this->nodeNameResolver->getName($node->expr);
            if ($propertyName) {
                $propertyNames[] = $propertyName;
            }
            return null;
        });
        return $propertyNames;
    }
    /**
     * Matches:
     * "$this->someValue = $<variableName>;"
     */
    private function isVariableAssignToThisPropertyFetch(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign $assign, string $variableName) : bool
    {
        if (!$assign->expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable) {
            return \false;
        }
        if (!$this->nodeNameResolver->isName($assign->expr, $variableName)) {
            return \false;
        }
        if (!$assign->var instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PropertyFetch) {
            return \false;
        }
        $propertyFetch = $assign->var;
        // must be local property
        return $this->nodeNameResolver->isName($propertyFetch->var, 'this');
    }
}
