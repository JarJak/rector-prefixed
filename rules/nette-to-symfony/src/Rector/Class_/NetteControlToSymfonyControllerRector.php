<?php

declare (strict_types=1);
namespace Rector\NetteToSymfony\Rector\Class_;

use _PhpScoper26e51eeacccf\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Nette\NodeFactory\ActionRenderFactory;
use Rector\Nette\TemplatePropertyAssignCollector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStan\Type\FullyQualifiedObjectType;
use _PhpScoper26e51eeacccf\Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use _PhpScoper26e51eeacccf\Symfony\Component\HttpFoundation\Response;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://doc.nette.org/en/2.4/components
 * ↓
 * @see https://symfony.com/doc/current/controller.html
 * @see \Rector\NetteToSymfony\Tests\Rector\Class_\NetteControlToSymfonyControllerRector\NetteControlToSymfonyControllerRectorTest
 */
final class NetteControlToSymfonyControllerRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var TemplatePropertyAssignCollector
     */
    private $templatePropertyAssignCollector;
    /**
     * @var ActionRenderFactory
     */
    private $actionRenderFactory;
    public function __construct(\Rector\Nette\NodeFactory\ActionRenderFactory $actionRenderFactory, \Rector\Nette\TemplatePropertyAssignCollector $templatePropertyAssignCollector)
    {
        $this->templatePropertyAssignCollector = $templatePropertyAssignCollector;
        $this->actionRenderFactory = $actionRenderFactory;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Migrate Nette Component to Symfony Controller', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Nette\Application\UI\Control;

class SomeControl extends Control
{
    public function render()
    {
        $this->template->param = 'some value';
        $this->template->render(__DIR__ . '/poll.latte');
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SomeController extends AbstractController
{
     public function some(): Response
     {
         return $this->render(__DIR__ . '/poll.latte', ['param' => 'some value']);
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
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->isAnonymousClass($node)) {
            return null;
        }
        // skip presenter
        if ($this->isName($node, '*Presenter')) {
            return null;
        }
        if (!$this->isObjectType($node, '_PhpScoper26e51eeacccf\\Nette\\Application\\UI\\Control')) {
            return null;
        }
        $className = (string) $node->name;
        $className = $this->removeSuffix($className, 'Control');
        $className .= 'Controller';
        $node->name = new \PhpParser\Node\Identifier($className);
        $node->extends = new \PhpParser\Node\Name\FullyQualified(\_PhpScoper26e51eeacccf\Symfony\Bundle\FrameworkBundle\Controller\AbstractController::class);
        $classMethod = $node->getMethod('render');
        if ($classMethod !== null) {
            $this->processRenderMethod($classMethod);
        }
        return $node;
    }
    private function removeSuffix(string $content, string $suffix) : string
    {
        if (!\_PhpScoper26e51eeacccf\Nette\Utils\Strings::endsWith($content, $suffix)) {
            return $content;
        }
        return \_PhpScoper26e51eeacccf\Nette\Utils\Strings::substring($content, 0, -\_PhpScoper26e51eeacccf\Nette\Utils\Strings::length($suffix));
    }
    private function processRenderMethod(\PhpParser\Node\Stmt\ClassMethod $classMethod) : void
    {
        $this->processGetPresenterGetSessionMethodCall($classMethod);
        $classMethod->name = new \PhpParser\Node\Identifier('action');
        $magicTemplatePropertyCalls = $this->templatePropertyAssignCollector->collectTemplateFileNameVariablesAndNodesToRemove($classMethod);
        $methodCall = $this->actionRenderFactory->createThisRenderMethodCall($magicTemplatePropertyCalls);
        // add return in the end
        $return = new \PhpParser\Node\Stmt\Return_($methodCall);
        $classMethod->stmts[] = $return;
        if ($this->isAtLeastPhpVersion(\Rector\Core\ValueObject\PhpVersionFeature::SCALAR_TYPES)) {
            $classMethod->returnType = new \PhpParser\Node\Name\FullyQualified(\_PhpScoper26e51eeacccf\Symfony\Component\HttpFoundation\Response::class);
        }
        $this->removeNodes($magicTemplatePropertyCalls->getNodesToRemove());
    }
    private function processGetPresenterGetSessionMethodCall(\PhpParser\Node\Stmt\ClassMethod $classMethod) : void
    {
        $this->traverseNodesWithCallable((array) $classMethod->getStmts(), function (\PhpParser\Node $node) : ?MethodCall {
            if (!$node instanceof \PhpParser\Node\Expr\MethodCall) {
                return null;
            }
            if (!$this->isName($node->name, 'getSession')) {
                return null;
            }
            if (!$node->var instanceof \PhpParser\Node\Expr\MethodCall) {
                return null;
            }
            if (!$this->isName($node->var->name, 'getPresenter')) {
                return null;
            }
            $node->var = new \PhpParser\Node\Expr\PropertyFetch(new \PhpParser\Node\Expr\Variable('this'), 'session');
            $classLike = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
            if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
                throw new \Rector\Core\Exception\ShouldNotHappenException();
            }
            $this->addConstructorDependencyToClass($classLike, new \Rector\PHPStan\Type\FullyQualifiedObjectType('_PhpScoper26e51eeacccf\\Nette\\Http\\Session'), 'session');
            return $node;
        });
    }
}
