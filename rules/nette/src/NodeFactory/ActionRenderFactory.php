<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Nette\NodeFactory;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Arg;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayItem;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PropertyFetch;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\String_;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\NodeFactory;
use _PhpScoper0a2ac50786fa\Rector\Nette\ValueObject\MagicTemplatePropertyCalls;
final class ActionRenderFactory
{
    /**
     * @var NodeFactory
     */
    private $nodeFactory;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\NodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }
    public function createThisRenderMethodCall(\_PhpScoper0a2ac50786fa\Rector\Nette\ValueObject\MagicTemplatePropertyCalls $magicTemplatePropertyCalls) : \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall
    {
        $methodCall = $this->nodeFactory->createMethodCall('this', 'render');
        $this->addArguments($magicTemplatePropertyCalls, $methodCall);
        return $methodCall;
    }
    public function createThisTemplateRenderMethodCall(\_PhpScoper0a2ac50786fa\Rector\Nette\ValueObject\MagicTemplatePropertyCalls $magicTemplatePropertyCalls) : \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall
    {
        $thisTemplatePropertyFetch = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\PropertyFetch(new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable('this'), 'template');
        $methodCall = $this->nodeFactory->createMethodCall($thisTemplatePropertyFetch, 'render');
        $this->addArguments($magicTemplatePropertyCalls, $methodCall);
        return $methodCall;
    }
    private function addArguments(\_PhpScoper0a2ac50786fa\Rector\Nette\ValueObject\MagicTemplatePropertyCalls $magicTemplatePropertyCalls, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall $methodCall) : void
    {
        if ($magicTemplatePropertyCalls->getTemplateFileExpr() !== null) {
            $methodCall->args[0] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Arg($magicTemplatePropertyCalls->getTemplateFileExpr());
        }
        if ($magicTemplatePropertyCalls->getTemplateVariables() !== []) {
            $templateVariablesArray = $this->createTemplateVariablesArray($magicTemplatePropertyCalls->getTemplateVariables());
            $methodCall->args[1] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Arg($templateVariablesArray);
        }
    }
    /**
     * @param Expr[] $templateVariables
     */
    private function createTemplateVariablesArray(array $templateVariables) : \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_
    {
        $array = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_();
        foreach ($templateVariables as $name => $node) {
            $array->items[] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayItem($node, new \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\String_($name));
        }
        return $array;
    }
}
