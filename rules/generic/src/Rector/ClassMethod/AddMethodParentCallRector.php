<?php

declare (strict_types=1);
namespace Rector\Generic\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Generic\Tests\Rector\ClassMethod\AddMethodParentCallRector\AddMethodParentCallRectorTest
 */
final class AddMethodParentCallRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const METHODS_BY_PARENT_TYPES = 'methods_by_parent_type';
    /**
     * @var array<string, string>
     */
    private $methodsByParentTypes = [];
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Add method parent call, in case new parent method is added', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SunshineCommand extends ParentClassWithNewConstructor
{
    public function __construct()
    {
        $value = 5;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SunshineCommand extends ParentClassWithNewConstructor
{
    public function __construct()
    {
        $value = 5;

        parent::__construct();
    }
}
CODE_SAMPLE
, [self::METHODS_BY_PARENT_TYPES => ['ParentClassWithNewConstructor' => '__construct']])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $classLike = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return null;
        }
        /** @var string $className */
        $className = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        foreach ($this->methodsByParentTypes as $type => $method) {
            if (!$this->isObjectType($classLike, $type)) {
                continue;
            }
            // not itself
            if ($className === $type) {
                continue;
            }
            if ($this->shouldSkipMethod($node, $method)) {
                continue;
            }
            $node->stmts[] = $this->createParentStaticCall($method);
            return $node;
        }
        return null;
    }
    public function configure(array $configuration) : void
    {
        $this->methodsByParentTypes = $configuration[self::METHODS_BY_PARENT_TYPES] ?? [];
    }
    private function shouldSkipMethod(\PhpParser\Node\Stmt\ClassMethod $classMethod, string $method) : bool
    {
        if (!$this->isName($classMethod, $method)) {
            return \true;
        }
        return $this->hasParentCallOfMethod($classMethod, $method);
    }
    private function createParentStaticCall(string $method) : \PhpParser\Node\Stmt\Expression
    {
        $staticCall = new \PhpParser\Node\Expr\StaticCall(new \PhpParser\Node\Name('parent'), new \PhpParser\Node\Identifier($method));
        return new \PhpParser\Node\Stmt\Expression($staticCall);
    }
    /**
     * Looks for "parent::<methodName>
     */
    private function hasParentCallOfMethod(\PhpParser\Node\Stmt\ClassMethod $classMethod, string $method) : bool
    {
        return (bool) $this->betterNodeFinder->findFirst((array) $classMethod->stmts, function (\PhpParser\Node $node) use($method) : bool {
            return $this->isStaticCallNamed($node, 'parent', $method);
        });
    }
}
