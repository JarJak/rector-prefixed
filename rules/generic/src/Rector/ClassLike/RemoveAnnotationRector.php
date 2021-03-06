<?php

declare (strict_types=1);
namespace Rector\Generic\Rector\ClassLike;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use RectorPrefix20210118\Webmozart\Assert\Assert;
/**
 * @see \Rector\Generic\Tests\Rector\ClassLike\RemoveAnnotationRector\RemoveAnnotationRectorTest
 */
final class RemoveAnnotationRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const ANNOTATIONS_TO_REMOVE = 'annotations_to_remove';
    /**
     * @var string[]
     */
    private $annotationsToRemove = [];
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove annotation by names', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
/**
 * @method getName()
 */
final class SomeClass
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
}
CODE_SAMPLE
, [self::ANNOTATIONS_TO_REMOVE => ['method']])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassLike::class, \PhpParser\Node\FunctionLike::class, \PhpParser\Node\Stmt\Property::class, \PhpParser\Node\Stmt\ClassConst::class];
    }
    /**
     * @param ClassLike|FunctionLike|Property|ClassConst $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->annotationsToRemove === []) {
            return null;
        }
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return null;
        }
        $hasChanged = \false;
        foreach ($this->annotationsToRemove as $annotationToRemove) {
            if (!$phpDocInfo->hasByName($annotationToRemove)) {
                continue;
            }
            $phpDocInfo->removeByName($annotationToRemove);
            $hasChanged = \true;
        }
        if (!$hasChanged) {
            return null;
        }
        return $node;
    }
    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration) : void
    {
        $annotationsToRemove = $configuration[self::ANNOTATIONS_TO_REMOVE] ?? [];
        \RectorPrefix20210118\Webmozart\Assert\Assert::allString($annotationsToRemove);
        $this->annotationsToRemove = $annotationsToRemove;
    }
}
