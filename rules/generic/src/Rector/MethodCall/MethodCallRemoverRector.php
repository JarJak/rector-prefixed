<?php

declare (strict_types=1);
namespace Rector\Generic\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Generic\Tests\Rector\MethodCall\MethodCallRemoverRector\MethodCallRemoverRectorTest
 */
final class MethodCallRemoverRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const METHOD_CALL_REMOVER_ARGUMENT = '$methodCallRemoverArgument';
    /**
     * @var string[]
     */
    private $methodCallRemoverArgument = [];
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns "$this->something()->anything()" to "$this->anything()"', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
$someObject = new Car;
$someObject->something()->anything();
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$someObject = new Car;
$someObject->anything();
CODE_SAMPLE
, [self::METHOD_CALL_REMOVER_ARGUMENT => [self::METHOD_CALL_REMOVER_ARGUMENT => ['Car' => 'something']]])]);
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
        foreach ($this->methodCallRemoverArgument as $className => $methodName) {
            if (!$this->isObjectType($node->var, $className)) {
                continue;
            }
            if (!$this->isName($node->name, $methodName)) {
                continue;
            }
            return $node->var;
        }
        return $node;
    }
    public function configure(array $configuration) : void
    {
        $this->methodCallRemoverArgument = $configuration[self::METHOD_CALL_REMOVER_ARGUMENT] ?? [];
    }
}
