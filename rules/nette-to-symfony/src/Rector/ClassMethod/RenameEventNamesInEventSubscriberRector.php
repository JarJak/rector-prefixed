<?php

declare (strict_types=1);
namespace Rector\NetteToSymfony\Rector\ClassMethod;

use RectorPrefix20210118\Composer\Script\Event;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\NetteToSymfony\Event\EventInfosFactory;
use Rector\NetteToSymfony\ValueObject\EventInfo;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/contributte/event-dispatcher-extra/blob/master/.docs/README.md#bridge-wrench
 * @see https://symfony.com/doc/current/reference/events.html
 * @see https://symfony.com/doc/current/components/http_kernel.html#creating-an-event-listener
 * @see https://github.com/symfony/symfony/blob/master/src/Symfony/Component/HttpKernel/KernelEvents.php
 * @see \Rector\NetteToSymfony\Tests\Rector\ClassMethod\RenameEventNamesInEventSubscriberRector\RenameEventNamesInEventSubscriberRectorTest
 */
final class RenameEventNamesInEventSubscriberRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var EventInfo[]
     */
    private $symfonyClassConstWithAliases = [];
    public function __construct(\Rector\NetteToSymfony\Event\EventInfosFactory $eventInfosFactory)
    {
        $this->symfonyClassConstWithAliases = $eventInfosFactory->create();
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes event names from Nette ones to Symfony ones', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SomeClass implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return ['nette.application' => 'someMethod'];
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SomeClass implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [\SymfonyEvents::KERNEL => 'someMethod'];
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
        if (!$this->isObjectType($classLike, 'Symfony\\Component\\EventDispatcher\\EventSubscriberInterface')) {
            return null;
        }
        if (!$this->isName($node, 'getSubscribedEvents')) {
            return null;
        }
        /** @var Return_[] $returnNodes */
        $returnNodes = $this->betterNodeFinder->findInstanceOf($node, \PhpParser\Node\Stmt\Return_::class);
        foreach ($returnNodes as $returnNode) {
            if (!$returnNode->expr instanceof \PhpParser\Node\Expr\Array_) {
                continue;
            }
            $this->renameArrayKeys($returnNode);
        }
        return $node;
    }
    private function renameArrayKeys(\PhpParser\Node\Stmt\Return_ $return) : void
    {
        if (!$return->expr instanceof \PhpParser\Node\Expr\Array_) {
            return;
        }
        foreach ($return->expr->items as $arrayItem) {
            if ($arrayItem === null) {
                continue;
            }
            $eventInfo = $this->matchStringKeys($arrayItem);
            if ($eventInfo === null) {
                $eventInfo = $this->matchClassConstKeys($arrayItem);
            }
            if ($eventInfo === null) {
                continue;
            }
            $arrayItem->key = new \PhpParser\Node\Expr\ClassConstFetch(new \PhpParser\Node\Name\FullyQualified($eventInfo->getClass()), $eventInfo->getConstant());
            // method name
            $className = (string) $return->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
            $methodName = (string) $this->getValue($arrayItem->value);
            $this->processMethodArgument($className, $methodName, $eventInfo);
        }
    }
    private function matchStringKeys(\PhpParser\Node\Expr\ArrayItem $arrayItem) : ?\Rector\NetteToSymfony\ValueObject\EventInfo
    {
        if (!$arrayItem->key instanceof \PhpParser\Node\Scalar\String_) {
            return null;
        }
        foreach ($this->symfonyClassConstWithAliases as $symfonyClassConst) {
            foreach ($symfonyClassConst->getOldStringAliases() as $netteStringName) {
                if ($this->isValue($arrayItem->key, $netteStringName)) {
                    return $symfonyClassConst;
                }
            }
        }
        return null;
    }
    private function matchClassConstKeys(\PhpParser\Node\Expr\ArrayItem $arrayItem) : ?\Rector\NetteToSymfony\ValueObject\EventInfo
    {
        if (!$arrayItem->key instanceof \PhpParser\Node\Expr\ClassConstFetch) {
            return null;
        }
        foreach ($this->symfonyClassConstWithAliases as $symfonyClassConst) {
            $isMatch = $this->resolveClassConstAliasMatch($arrayItem, $symfonyClassConst);
            if ($isMatch) {
                return $symfonyClassConst;
            }
        }
        return null;
    }
    private function processMethodArgument(string $class, string $method, \Rector\NetteToSymfony\ValueObject\EventInfo $eventInfo) : void
    {
        $classMethodNode = $this->nodeRepository->findClassMethod($class, $method);
        if ($classMethodNode === null) {
            return;
        }
        if (\count($classMethodNode->params) !== 1) {
            return;
        }
        $classMethodNode->params[0]->type = new \PhpParser\Node\Name\FullyQualified($eventInfo->getEventClass());
    }
    private function resolveClassConstAliasMatch(\PhpParser\Node\Expr\ArrayItem $arrayItem, \Rector\NetteToSymfony\ValueObject\EventInfo $eventInfo) : bool
    {
        foreach ($eventInfo->getOldClassConstAliases() as $netteClassConst) {
            $classConstFetchNode = $arrayItem->key;
            if ($classConstFetchNode === null) {
                continue;
            }
            if ($this->isName($classConstFetchNode, $netteClassConst)) {
                return \true;
            }
        }
        return \false;
    }
}
