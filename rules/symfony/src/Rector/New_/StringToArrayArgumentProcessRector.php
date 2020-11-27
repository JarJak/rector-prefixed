<?php

declare (strict_types=1);
namespace Rector\Symfony\Rector\New_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\StringType;
use Rector\Core\PhpParser\NodeTransformer;
use Rector\Core\Rector\AbstractRector;
use _PhpScoper006a73f0e455\Symfony\Component\Console\Input\StringInput;
use Symplify\PackageBuilder\Reflection\PrivatesCaller;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/symfony/symfony/pull/27821/files
 * @see \Rector\Symfony\Tests\Rector\New_\StringToArrayArgumentProcessRector\StringToArrayArgumentProcessRectorTest
 */
final class StringToArrayArgumentProcessRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var NodeTransformer
     */
    private $nodeTransformer;
    public function __construct(\Rector\Core\PhpParser\NodeTransformer $nodeTransformer)
    {
        $this->nodeTransformer = $nodeTransformer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes Process string argument to an array', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\Process\Process;
$process = new Process('ls -l');
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\Process\Process;
$process = new Process(['ls', '-l']);
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\New_::class, \PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param New_|MethodCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $expr = $node instanceof \PhpParser\Node\Expr\New_ ? $node->class : $node->var;
        if ($this->isObjectType($expr, '_PhpScoper006a73f0e455\\Symfony\\Component\\Process\\Process')) {
            return $this->processArgumentPosition($node, 0);
        }
        if ($this->isObjectType($expr, '_PhpScoper006a73f0e455\\Symfony\\Component\\Console\\Helper\\ProcessHelper')) {
            return $this->processArgumentPosition($node, 1);
        }
        return null;
    }
    /**
     * @param New_|MethodCall $node
     */
    private function processArgumentPosition(\PhpParser\Node $node, int $argumentPosition) : ?\PhpParser\Node
    {
        if (!isset($node->args[$argumentPosition])) {
            return null;
        }
        $firstArgument = $node->args[$argumentPosition]->value;
        if ($firstArgument instanceof \PhpParser\Node\Expr\Array_) {
            return null;
        }
        // type analyzer
        if ($this->isStaticType($firstArgument, \PHPStan\Type\StringType::class)) {
            $this->processStringType($node, $argumentPosition, $firstArgument);
        }
        return $node;
    }
    /**
     * @param New_|MethodCall $node
     */
    private function processStringType(\PhpParser\Node $node, int $argumentPosition, \PhpParser\Node $firstArgument) : void
    {
        if ($firstArgument instanceof \PhpParser\Node\Expr\BinaryOp\Concat) {
            $arrayNode = $this->nodeTransformer->transformConcatToStringArray($firstArgument);
            if ($arrayNode !== null) {
                $node->args[$argumentPosition] = new \PhpParser\Node\Arg($arrayNode);
            }
            return;
        }
        if ($this->isFuncCallName($firstArgument, 'sprintf')) {
            /** @var FuncCall $firstArgument */
            $arrayNode = $this->nodeTransformer->transformSprintfToArray($firstArgument);
            if ($arrayNode !== null) {
                $node->args[$argumentPosition]->value = $arrayNode;
            }
        } elseif ($firstArgument instanceof \PhpParser\Node\Scalar\String_) {
            $parts = $this->splitProcessCommandToItems($firstArgument->value);
            $node->args[$argumentPosition]->value = $this->createArray($parts);
        }
        $this->processPreviousAssign($node, $firstArgument);
    }
    /**
     * @return string[]
     */
    private function splitProcessCommandToItems(string $process) : array
    {
        $privatesCaller = new \Symplify\PackageBuilder\Reflection\PrivatesCaller();
        return $privatesCaller->callPrivateMethod(new \_PhpScoper006a73f0e455\Symfony\Component\Console\Input\StringInput(''), 'tokenize', $process);
    }
    private function processPreviousAssign(\PhpParser\Node $node, \PhpParser\Node $firstArgument) : void
    {
        $previousNodeAssign = $this->findPreviousNodeAssign($node, $firstArgument);
        if ($previousNodeAssign === null) {
            return;
        }
        if (!$this->isFuncCallName($previousNodeAssign->expr, 'sprintf')) {
            return;
        }
        /** @var FuncCall $funcCall */
        $funcCall = $previousNodeAssign->expr;
        $arrayNode = $this->nodeTransformer->transformSprintfToArray($funcCall);
        if ($arrayNode !== null) {
            $previousNodeAssign->expr = $arrayNode;
        }
    }
    private function findPreviousNodeAssign(\PhpParser\Node $node, \PhpParser\Node $firstArgument) : ?\PhpParser\Node\Expr\Assign
    {
        /** @var Assign|null $assign */
        $assign = $this->betterNodeFinder->findFirstPrevious($node, function (\PhpParser\Node $checkedNode) use($firstArgument) : ?Assign {
            if (!$checkedNode instanceof \PhpParser\Node\Expr\Assign) {
                return null;
            }
            if (!$this->areNodesEqual($checkedNode->var, $firstArgument)) {
                return null;
            }
            return $checkedNode;
        });
        return $assign;
    }
}