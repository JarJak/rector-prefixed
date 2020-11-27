<?php

declare (strict_types=1);
namespace Rector\CodingStyle\Rector\ClassMethod;

use Iterator;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\NodeTransformer;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use _PhpScoper006a73f0e455\Webmozart\Assert\Assert;
/**
 * @see https://medium.com/tech-tajawal/use-memory-gently-with-yield-in-php-7e62e2480b8d
 * @see https://3v4l.org/5PJid
 *
 * @see \Rector\CodingStyle\Tests\Rector\ClassMethod\ReturnArrayClassMethodToYieldRector\ReturnArrayClassMethodToYieldRectorTest
 */
final class ReturnArrayClassMethodToYieldRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const METHODS_TO_YIELDS = 'methods_to_yields';
    /**
     * @var ReturnArrayClassMethodToyield[]
     */
    private $methodsToYields = [];
    /**
     * @var Comment[]
     */
    private $returnComments = [];
    /**
     * @var NodeTransformer
     */
    private $nodeTransformer;
    /**
     * @var PhpDocInfo|null
     */
    private $returnPhpDocInfo;
    public function __construct(\Rector\Core\PhpParser\NodeTransformer $nodeTransformer)
    {
        $this->nodeTransformer = $nodeTransformer;
        // default values
        $this->methodsToYields = [new \Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield('_PhpScoper006a73f0e455\\PHPUnit\\Framework\\TestCase', 'provideData'), new \Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield('_PhpScoper006a73f0e455\\PHPUnit\\Framework\\TestCase', 'provideData*'), new \Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield('_PhpScoper006a73f0e455\\PHPUnit\\Framework\\TestCase', 'dataProvider'), new \Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield('_PhpScoper006a73f0e455\\PHPUnit\\Framework\\TestCase', 'dataProvider*')];
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns array return to yield return in specific type and method', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return ['event' => 'callback'];
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        yield 'event' => 'callback';
    }
}
CODE_SAMPLE
, [self::METHODS_TO_YIELDS => [new \Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield('EventSubscriberInterface', 'getSubscribedEvents')]])]);
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
        $hasChanged = \false;
        foreach ($this->methodsToYields as $methodToYield) {
            if (!$this->isObjectType($node, $methodToYield->getType())) {
                continue;
            }
            if (!$this->isName($node, $methodToYield->getMethod())) {
                continue;
            }
            $arrayNode = $this->collectReturnArrayNodesFromClassMethod($node);
            if ($arrayNode === null) {
                continue;
            }
            $this->transformArrayToYieldsOnMethodNode($node, $arrayNode);
            $this->completeComments($node);
            $hasChanged = \true;
        }
        if (!$hasChanged) {
            return null;
        }
        return $node;
    }
    public function configure(array $configuration) : void
    {
        $methodsToYields = $configuration[self::METHODS_TO_YIELDS] ?? [];
        \_PhpScoper006a73f0e455\Webmozart\Assert\Assert::allIsInstanceOf($methodsToYields, \Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield::class);
        $this->methodsToYields = $methodsToYields;
    }
    private function collectReturnArrayNodesFromClassMethod(\PhpParser\Node\Stmt\ClassMethod $classMethod) : ?\PhpParser\Node\Expr\Array_
    {
        if ($classMethod->stmts === null) {
            return null;
        }
        foreach ($classMethod->stmts as $statement) {
            if ($statement instanceof \PhpParser\Node\Stmt\Return_) {
                if (!$statement->expr instanceof \PhpParser\Node\Expr\Array_) {
                    continue;
                }
                $this->returnPhpDocInfo = $statement->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
                $this->returnComments = $statement->getComments();
                return $statement->expr;
            }
        }
        return null;
    }
    private function transformArrayToYieldsOnMethodNode(\PhpParser\Node\Stmt\ClassMethod $classMethod, \PhpParser\Node\Expr\Array_ $array) : void
    {
        $yieldNodes = $this->nodeTransformer->transformArrayToYields($array);
        // remove whole return node
        $parentNode = $array->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parentNode === null) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        $this->removeReturnTag($classMethod);
        // change return typehint
        $classMethod->returnType = new \PhpParser\Node\Name\FullyQualified(\Iterator::class);
        foreach ((array) $classMethod->stmts as $key => $classMethodStmt) {
            if (!$classMethodStmt instanceof \PhpParser\Node\Stmt\Return_) {
                continue;
            }
            unset($classMethod->stmts[$key]);
        }
        $classMethod->stmts = \array_merge((array) $classMethod->stmts, $yieldNodes);
    }
    private function completeComments(\PhpParser\Node\Stmt\ClassMethod $classMethod) : void
    {
        if ($this->returnPhpDocInfo === null && $this->returnComments === []) {
            return;
        }
        $classMethod->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO, $this->returnPhpDocInfo);
        $classMethod->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::COMMENTS, $this->returnComments);
    }
    private function removeReturnTag(\PhpParser\Node\Stmt\ClassMethod $classMethod) : void
    {
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return;
        }
        $phpDocInfo->removeByType(\PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode::class);
    }
}