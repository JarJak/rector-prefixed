<?php

declare (strict_types=1);
namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\PhpParser\Node\Manipulator\ClassMethodManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\NodeManipulator\ControllerClassMethodManipulator;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DeadCode\Tests\Rector\ClassMethod\RemoveEmptyClassMethodRector\RemoveEmptyClassMethodRectorTest
 */
final class RemoveEmptyClassMethodRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var ClassMethodManipulator
     */
    private $classMethodManipulator;
    /**
     * @var ControllerClassMethodManipulator
     */
    private $controllerClassMethodManipulator;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\ClassMethodManipulator $classMethodManipulator, \Rector\DeadCode\NodeManipulator\ControllerClassMethodManipulator $controllerClassMethodManipulator)
    {
        $this->classMethodManipulator = $classMethodManipulator;
        $this->controllerClassMethodManipulator = $controllerClassMethodManipulator;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove empty method calls not required by parents', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class OrphanClass
{
    public function __construct()
    {
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class OrphanClass
{
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $classLike = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return null;
        }
        if ($node->stmts !== null && $node->stmts !== []) {
            return null;
        }
        if ($node->isAbstract()) {
            return null;
        }
        if ($this->shouldSkipNonFinalNonPrivateClassMethod($classLike, $node)) {
            return null;
        }
        if ($this->classMethodManipulator->isNamedConstructor($node)) {
            return null;
        }
        if ($this->classMethodManipulator->hasParentMethodOrInterfaceMethod($node)) {
            return null;
        }
        if ($this->controllerClassMethodManipulator->isControllerClassMethodWithBehaviorAnnotation($node)) {
            return null;
        }
        $this->removeNode($node);
        return $node;
    }
    private function shouldSkipNonFinalNonPrivateClassMethod(\PhpParser\Node\Stmt\Class_ $class, \PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        if ($class->isFinal()) {
            return \false;
        }
        if ($this->isName($classMethod, '__*')) {
            return \false;
        }
        if ($classMethod->isProtected()) {
            return \true;
        }
        return $classMethod->isPublic();
    }
}