<?php

declare (strict_types=1);
namespace Rector\Symfony\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Symfony\Bridge\NodeAnalyzer\ControllerMethodAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Symfony\Tests\Rector\ClassMethod\GetRequestRector\GetRequestRectorTest
 */
final class GetRequestRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const REQUEST_CLASS = '_PhpScoper006a73f0e455\\Symfony\\Component\\HttpFoundation\\Request';
    /**
     * @var string
     */
    private $requestVariableAndParamName;
    /**
     * @var ControllerMethodAnalyzer
     */
    private $controllerMethodAnalyzer;
    public function __construct(\Rector\Symfony\Bridge\NodeAnalyzer\ControllerMethodAnalyzer $controllerMethodAnalyzer)
    {
        $this->controllerMethodAnalyzer = $controllerMethodAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns fetching of dependencies via `$this->get()` to constructor injection in Command and Controller in Symfony', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeController
{
    public function someAction()
    {
        $this->getRequest()->...();
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\HttpFoundation\Request;

class SomeController
{
    public function someAction(Request $request)
    {
        $request->...();
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
        return [\PhpParser\Node\Stmt\ClassMethod::class, \PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param ClassMethod|MethodCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($node instanceof \PhpParser\Node\Stmt\ClassMethod) {
            $this->requestVariableAndParamName = $this->resolveUniqueName($node, 'request');
        }
        if ($this->isGetRequestInAction($node)) {
            return new \PhpParser\Node\Expr\Variable($this->requestVariableAndParamName);
        }
        if ($this->isActionWithGetRequestInBody($node)) {
            $node->params[] = new \PhpParser\Node\Param(new \PhpParser\Node\Expr\Variable($this->requestVariableAndParamName), null, new \PhpParser\Node\Name\FullyQualified(self::REQUEST_CLASS));
            return $node;
        }
        return null;
    }
    /**
     * @param ClassMethod|MethodCall $node
     */
    private function resolveUniqueName(\PhpParser\Node $node, string $name) : string
    {
        $candidates = $node instanceof \PhpParser\Node\Stmt\ClassMethod ? $node->params : $node->args;
        $candidateNames = [];
        foreach ($candidates as $candidate) {
            $candidateNames[] = $this->getName($candidate);
        }
        $bareName = $name;
        $prefixes = ['main', 'default'];
        while (\in_array($name, $candidateNames, \true)) {
            $name = \array_shift($prefixes) . \ucfirst($bareName);
        }
        return $name;
    }
    private function isGetRequestInAction(\PhpParser\Node $node) : bool
    {
        if (!$node instanceof \PhpParser\Node\Expr\MethodCall) {
            return \false;
        }
        if (!$node->var instanceof \PhpParser\Node\Expr\Variable) {
            return \false;
        }
        // must be $this->getRequest() in controller
        if (!$this->isVariableName($node->var, 'this')) {
            return \false;
        }
        if (!$this->isName($node->name, 'getRequest') && !$this->isGetMethodCallWithRequestParameters($node)) {
            return \false;
        }
        $classMethod = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::METHOD_NODE);
        if ($classMethod === null) {
            return \false;
        }
        return $this->controllerMethodAnalyzer->isAction($classMethod);
    }
    private function isActionWithGetRequestInBody(\PhpParser\Node $node) : bool
    {
        if (!$this->controllerMethodAnalyzer->isAction($node)) {
            return \false;
        }
        $containsGetRequestMethod = $this->containsGetRequestMethod($node);
        if ($containsGetRequestMethod) {
            return \true;
        }
        // "$this->get('request')"
        /** @var MethodCall[] $getMethodCalls */
        $getMethodCalls = $this->betterNodeFinder->find($node, function (\PhpParser\Node $node) : bool {
            return $this->isLocalMethodCallNamed($node, 'get');
        });
        foreach ($getMethodCalls as $getMethodCall) {
            if ($this->isGetMethodCallWithRequestParameters($getMethodCall)) {
                return \true;
            }
        }
        return \false;
    }
    private function isGetMethodCallWithRequestParameters(\PhpParser\Node\Expr\MethodCall $methodCall) : bool
    {
        if (!$this->isName($methodCall->name, 'get')) {
            return \false;
        }
        if (\count($methodCall->args) !== 1) {
            return \false;
        }
        if (!$methodCall->args[0]->value instanceof \PhpParser\Node\Scalar\String_) {
            return \false;
        }
        /** @var String_ $stringValue */
        $stringValue = $methodCall->args[0]->value;
        return $stringValue->value === 'request';
    }
    private function containsGetRequestMethod(\PhpParser\Node $node) : bool
    {
        // "$this->getRequest()"
        return (bool) $this->betterNodeFinder->find($node, function (\PhpParser\Node $node) : bool {
            return $this->isLocalMethodCallNamed($node, 'getRequest');
        });
    }
}