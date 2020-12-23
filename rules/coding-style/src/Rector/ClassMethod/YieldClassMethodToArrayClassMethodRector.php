<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\CodingStyle\Rector\ClassMethod;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Yield_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Identifier;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Expression;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Return_;
use _PhpScoper0a2ac50786fa\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\NodeTransformer;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://medium.com/tech-tajawal/use-memory-gently-with-yield-in-php-7e62e2480b8d
 * @see https://3v4l.org/5PJid
 *
 * @see \Rector\CodingStyle\Tests\Rector\ClassMethod\YieldClassMethodToArrayClassMethodRector\YieldClassMethodToArrayClassMethodRectorTest
 */
final class YieldClassMethodToArrayClassMethodRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector implements \_PhpScoper0a2ac50786fa\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const METHODS_BY_TYPE = '$methodsByType';
    /**
     * @var string[][]
     */
    private $methodsByType = [];
    /**
     * @var NodeTransformer
     */
    private $nodeTransformer;
    /**
     * @param string[][] $methodsByType
     */
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\NodeTransformer $nodeTransformer, array $methodsByType = [])
    {
        $this->methodsByType = $methodsByType;
        $this->nodeTransformer = $nodeTransformer;
    }
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns yield return to array return in specific type and method', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        yield 'event' => 'callback';
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return ['event' => 'callback'];
    }
}
CODE_SAMPLE
, [self::METHODS_BY_TYPE => ['EventSubscriberInterface' => ['getSubscribedEvents']]])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        foreach ($this->methodsByType as $type => $methods) {
            if (!$this->isObjectType($node, $type)) {
                continue;
            }
            foreach ($methods as $method) {
                if (!$this->isName($node, $method)) {
                    continue;
                }
                $yieldNodes = $this->collectYieldNodesFromClassMethod($node);
                if ($yieldNodes === []) {
                    continue;
                }
                $arrayNode = $this->nodeTransformer->transformYieldsToArray($yieldNodes);
                $this->removeNodes($yieldNodes);
                $node->returnType = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Identifier('array');
                $returnExpression = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Return_($arrayNode);
                $node->stmts = \array_merge((array) $node->stmts, [$returnExpression]);
            }
        }
        return $node;
    }
    public function configure(array $configuration) : void
    {
        $this->methodsByType = $configuration[self::METHODS_BY_TYPE] ?? [];
    }
    /**
     * @return Yield_[]
     */
    private function collectYieldNodesFromClassMethod(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod) : array
    {
        $yieldNodes = [];
        if ($classMethod->stmts === null) {
            return [];
        }
        foreach ($classMethod->stmts as $statement) {
            if (!$statement instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Expression) {
                continue;
            }
            if ($statement->expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Yield_) {
                $yieldNodes[] = $statement->expr;
            }
        }
        return $yieldNodes;
    }
}
