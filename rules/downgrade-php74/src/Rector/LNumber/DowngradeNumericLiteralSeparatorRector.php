<?php

declare (strict_types=1);
namespace Rector\DowngradePhp74\Rector\LNumber;

use PhpParser\Node;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://wiki.php.net/rfc/numeric_literal_separator
 * @see \Rector\DowngradePhp74\Tests\Rector\LNumber\DowngradeNumericLiteralSeparatorRector\DowngradeNumericLiteralSeparatorRectorTest
 */
final class DowngradeNumericLiteralSeparatorRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove "_" as thousands separator in numbers', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $int = 1_000;
        $float = 1_000_500.001;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $int = 1000;
        $float = 1000500.001;
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
        return [\PhpParser\Node\Scalar\LNumber::class, \PhpParser\Node\Scalar\DNumber::class];
    }
    /**
     * @param LNumber|DNumber $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->shouldRefactor($node)) {
            return null;
        }
        $node->value = (string) $node->value;
        return $node;
    }
    /**
     * @param LNumber|DNumber $node
     */
    public function shouldRefactor(\PhpParser\Node $node) : bool
    {
        // "_" notation can be applied to decimal numbers only
        if ($node instanceof \PhpParser\Node\Scalar\LNumber) {
            return $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::KIND) === \PhpParser\Node\Scalar\LNumber::KIND_DEC;
        }
        return \true;
    }
}