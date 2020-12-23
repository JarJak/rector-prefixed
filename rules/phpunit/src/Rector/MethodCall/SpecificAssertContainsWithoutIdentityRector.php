<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\PHPUnit\Rector\MethodCall;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\StaticCall;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Identifier;
use _PhpScoper0a2ac50786fa\PHPStan\Type\StringType;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractPHPUnitRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/sebastianbergmann/phpunit/issues/3426
 * @see \Rector\PHPUnit\Tests\Rector\MethodCall\SpecificAssertContainsWithoutIdentityRector\SpecificAssertContainsWithoutIdentityRectorTest
 */
final class SpecificAssertContainsWithoutIdentityRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractPHPUnitRector
{
    /**
     * @var array<string, array<string, string>>
     */
    private const OLD_METHODS_NAMES_TO_NEW_NAMES = ['string' => ['assertContains' => 'assertContainsEquals', 'assertNotContains' => 'assertNotContainsEquals']];
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change assertContains()/assertNotContains() with non-strict comparison to new specific alternatives', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $objects = [ new \stdClass(), new \stdClass(), new \stdClass() ];
        $this->assertContains(new \stdClass(), $objects, 'message', false, false);
        $this->assertNotContains(new \stdClass(), $objects, 'message', false, false);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeTest extends TestCase
{
    public function test()
    {
        $objects = [ new \stdClass(), new \stdClass(), new \stdClass() ];
        $this->assertContainsEquals(new \stdClass(), $objects, 'message');
        $this->assertNotContainsEquals(new \stdClass(), $objects, 'message');
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
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        if (!$this->isPHPUnitMethodNames($node, ['assertContains', 'assertNotContains'])) {
            return null;
        }
        //when second argument is string: do nothing
        if ($this->isStaticType($node->args[1]->value, \_PhpScoper0a2ac50786fa\PHPStan\Type\StringType::class)) {
            return null;
        }
        //when less then 5 arguments given: do nothing
        if (!isset($node->args[4]) || $node->args[4]->value === null) {
            return null;
        }
        //when 5th argument check identity is true: do nothing
        if ($this->isValue($node->args[4]->value, \true)) {
            return null;
        }
        /* here we search for element of array without identity check  and we can replace functions */
        $methodName = $this->getName($node->name);
        $node->name = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Identifier(self::OLD_METHODS_NAMES_TO_NEW_NAMES['string'][$methodName]);
        unset($node->args[3], $node->args[4]);
        return $node;
    }
}
