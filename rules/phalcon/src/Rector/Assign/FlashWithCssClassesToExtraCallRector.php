<?php

declare (strict_types=1);
namespace Rector\Phalcon\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/rectorphp/rector/issues/2408#issue-534441142
 *
 * @see \Rector\Phalcon\Tests\Rector\Assign\FlashWithCssClassesToExtraCallRector\FlashWithCssClassesToExtraCallRectorTest
 */
final class FlashWithCssClassesToExtraCallRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Add $cssClasses in Flash to separated method call', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $cssClasses = [];
        $flash = new Phalcon\Flash($cssClasses);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $cssClasses = [];
        $flash = new Phalcon\Flash();
        $flash->setCssClasses($cssClasses);
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
        return [\PhpParser\Node\Expr\Assign::class];
    }
    /**
     * @param Assign $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$node->expr instanceof \PhpParser\Node\Expr\New_) {
            return null;
        }
        if (!$this->isName($node->expr->class, '_PhpScopera143bcca66cb\\Phalcon\\Flash')) {
            return null;
        }
        if (!isset($node->expr->args[0])) {
            return null;
        }
        $argument = $node->expr->args[0];
        // remove arg
        unset($node->expr->args[0]);
        // change the node
        $variable = $node->var;
        $setCssClassesMethodCall = new \PhpParser\Node\Expr\MethodCall($variable, 'setCssClasses', [$argument]);
        $this->addNodeAfterNode($setCssClassesMethodCall, $node);
        return $node;
    }
}
