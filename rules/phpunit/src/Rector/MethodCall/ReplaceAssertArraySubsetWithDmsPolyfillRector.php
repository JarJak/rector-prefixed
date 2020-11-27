<?php

declare (strict_types=1);
namespace Rector\PHPUnit\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractPHPUnitRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/sebastianbergmann/phpunit/issues/3494#issuecomment-480283612
 * @see https://github.com/sebastianbergmann/phpunit/issues/3495
 *
 * @see \Rector\PHPUnit\Tests\Rector\MethodCall\ReplaceAssertArraySubsetWithDmsPolyfillRector\ReplaceAssertArraySubsetWithDmsPolyfillRectorTest
 */
final class ReplaceAssertArraySubsetWithDmsPolyfillRector extends \Rector\Core\Rector\AbstractPHPUnitRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('_PhpScoper006a73f0e455\\Change assertArraySubset() to static call of DMS\\PHPUnitExtensions\\ArraySubset\\Assert', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

class SomeClass extends TestCase
{
    public function test()
    {
        self::assertArraySubset(['bar' => 0], ['bar' => '0'], true);

        $this->assertArraySubset(['bar' => 0], ['bar' => '0'], true);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use PHPUnit\Framework\TestCase;

class SomeClass extends TestCase
{
    public function test()
    {
        \DMS\PHPUnitExtensions\ArraySubset\Assert::assertArraySubset(['bar' => 0], ['bar' => '0'], true);

        \DMS\PHPUnitExtensions\ArraySubset\Assert::assertArraySubset(['bar' => 0], ['bar' => '0'], true);
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
        return [\PhpParser\Node\Expr\MethodCall::class, \PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isPHPUnitMethodName($node, 'assertArraySubset')) {
            return null;
        }
        return $this->createStaticCall('_PhpScoper006a73f0e455\\DMS\\PHPUnitExtensions\\ArraySubset\\Assert', 'assertArraySubset', $node->args);
    }
}
