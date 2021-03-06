<?php

declare (strict_types=1);
namespace Rector\Phalcon\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/rectorphp/rector/issues/2571
 *
 * @see \Rector\Phalcon\Tests\Rector\MethodCall\DecoupleSaveMethodCallWithArgumentToAssignRector\DecoupleSaveMethodCallWithArgumentToAssignRectorTest
 */
final class DecoupleSaveMethodCallWithArgumentToAssignRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Decouple Phalcon\\Mvc\\Model::save() with argument to assign()', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run(\Phalcon\Mvc\Model $model, $data)
    {
        $model->save($data);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(\Phalcon\Mvc\Model $model, $data)
    {
        $model->save();
        $model->assign($data);
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
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param MethodCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isObjectType($node->var, 'Phalcon\\Mvc\\Model')) {
            return null;
        }
        if (!$this->isName($node->name, 'save')) {
            return null;
        }
        if ($node->args === []) {
            return null;
        }
        $assignMethodCall = $this->createMethodCall($node->var, 'assign');
        $assignMethodCall->args = $node->args;
        $node->args = [];
        $this->addNodeAfterNode($assignMethodCall, $node);
        return $node;
    }
}
