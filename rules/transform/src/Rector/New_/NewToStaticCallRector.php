<?php

declare (strict_types=1);
namespace Rector\Transform\Rector\New_;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Transform\ValueObject\NewToStaticCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use _PhpScoper26e51eeacccf\Webmozart\Assert\Assert;
/**
 * @see \Rector\Transform\Tests\Rector\New_\NewToStaticCallRector\NewToStaticCallRectorTest
 */
final class NewToStaticCallRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const TYPE_TO_STATIC_CALLS = 'type_to_static_calls';
    /**
     * @var NewToStaticCall[]
     */
    private $typeToStaticCalls = [];
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change new Object to static call', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        new Cookie($name);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        Cookie::create($name);
    }
}
CODE_SAMPLE
, [self::TYPE_TO_STATIC_CALLS => [new \Rector\Transform\ValueObject\NewToStaticCall('Cookie', 'Cookie', 'create')]])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\New_::class];
    }
    /**
     * @param New_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        foreach ($this->typeToStaticCalls as $typeToStaticCall) {
            if (!$this->isObjectType($node->class, $typeToStaticCall->getType())) {
                continue;
            }
            return $this->createStaticCall($typeToStaticCall->getStaticCallClass(), $typeToStaticCall->getStaticCallMethod(), $node->args);
        }
        return null;
    }
    public function configure(array $configuration) : void
    {
        $typeToStaticCalls = $configuration[self::TYPE_TO_STATIC_CALLS] ?? [];
        \_PhpScoper26e51eeacccf\Webmozart\Assert\Assert::allIsInstanceOf($typeToStaticCalls, \Rector\Transform\ValueObject\NewToStaticCall::class);
        $this->typeToStaticCalls = $typeToStaticCalls;
    }
}
