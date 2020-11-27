<?php

declare (strict_types=1);
namespace Rector\Php71\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Type\NullType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://3v4l.org/Bndc9
 *
 * @see \Rector\Php71\Tests\Rector\FuncCall\CountOnNullRector\CountOnNullRectorTest
 */
final class CountOnNullRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const ALREADY_CHANGED_ON_COUNT = 'already_changed_on_count';
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes count() on null to safe ternary check', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$values = null;
$count = count($values);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$values = null;
$count = is_array($values) || $values instanceof Countable ? count($values) : 0;
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param FuncCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $countedNode = $node->args[0]->value;
        if ($this->isCountableType($countedNode)) {
            return null;
        }
        if ($this->isNullableType($countedNode) || $this->isStaticType($countedNode, \PHPStan\Type\NullType::class)) {
            $identical = new \PhpParser\Node\Expr\BinaryOp\Identical($countedNode, $this->createNull());
            $ternaryNode = new \PhpParser\Node\Expr\Ternary($identical, new \PhpParser\Node\Scalar\LNumber(0), $node);
        } else {
            if ($this->isAtLeastPhpVersion(\Rector\Core\ValueObject\PhpVersionFeature::IS_COUNTABLE)) {
                $conditionNode = new \PhpParser\Node\Expr\FuncCall(new \PhpParser\Node\Name('is_countable'), [new \PhpParser\Node\Arg($countedNode)]);
            } else {
                $instanceof = new \PhpParser\Node\Expr\Instanceof_($countedNode, new \PhpParser\Node\Name\FullyQualified('Countable'));
                $conditionNode = new \PhpParser\Node\Expr\BinaryOp\BooleanOr($this->createFuncCall('is_array', [new \PhpParser\Node\Arg($countedNode)]), $instanceof);
            }
            $ternaryNode = new \PhpParser\Node\Expr\Ternary($conditionNode, $node, new \PhpParser\Node\Scalar\LNumber(0));
        }
        // prevent infinity loop re-resolution
        $node->setAttribute(self::ALREADY_CHANGED_ON_COUNT, \true);
        return $ternaryNode;
    }
    private function shouldSkip(\PhpParser\Node\Expr\FuncCall $funcCall) : bool
    {
        if (!$this->isName($funcCall, 'count')) {
            return \true;
        }
        $alreadyChangedOnCount = $funcCall->getAttribute(self::ALREADY_CHANGED_ON_COUNT);
        // check if it has some condition before already, if so, probably it's already handled
        if ($alreadyChangedOnCount) {
            return \true;
        }
        $parentNode = $funcCall->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parentNode instanceof \PhpParser\Node\Expr\Ternary) {
            return \true;
        }
        if (!isset($funcCall->args[0])) {
            return \true;
        }
        // skip node in trait, as impossible to analyse
        $classLike = $funcCall->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        return $classLike instanceof \PhpParser\Node\Stmt\Trait_;
    }
}