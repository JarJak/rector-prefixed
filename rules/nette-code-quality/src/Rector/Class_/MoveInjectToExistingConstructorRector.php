<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\NetteCodeQuality\Rector\Class_;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Rector\Core\ValueObject\MethodName;
use _PhpScoper0a2ac50786fa\Rector\FamilyTree\NodeAnalyzer\PropertyUsageAnalyzer;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @sponsor Thanks https://amateri.com for sponsoring this rule - visit them on https://www.startupjobs.cz/startup/scrumworks-s-r-o
 *
 * @see \Rector\NetteCodeQuality\Tests\Rector\Class_\MoveInjectToExistingConstructorRector\MoveInjectToExistingConstructorRectorTest
 */
final class MoveInjectToExistingConstructorRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    /**
     * @var PropertyUsageAnalyzer
     */
    private $propertyUsageAnalyzer;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\FamilyTree\NodeAnalyzer\PropertyUsageAnalyzer $propertyUsageAnalyzer)
    {
        $this->propertyUsageAnalyzer = $propertyUsageAnalyzer;
    }
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Move @inject properties to constructor, if there already is one', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var SomeDependency
     * @inject
     */
    public $someDependency;

    /**
     * @var OtherDependency
     */
    private $otherDependency;

    public function __construct(OtherDependency $otherDependency)
    {
        $this->otherDependency = $otherDependency;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var SomeDependency
     */
    private $someDependency;

    /**
     * @var OtherDependency
     */
    private $otherDependency;

    public function __construct(OtherDependency $otherDependency, SomeDependency $someDependency)
    {
        $this->otherDependency = $otherDependency;
        $this->someDependency = $someDependency;
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
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        $injectProperties = $this->getInjectProperties($node);
        if ($injectProperties === []) {
            return null;
        }
        $constructClassMethod = $node->getMethod(\_PhpScoper0a2ac50786fa\Rector\Core\ValueObject\MethodName::CONSTRUCT);
        if ($constructClassMethod === null) {
            return null;
        }
        foreach ($injectProperties as $injectProperty) {
            $this->removeInjectAnnotation($injectProperty);
            $this->changePropertyVisibility($injectProperty);
            $this->addPropertyToCollector($injectProperty);
        }
        return $node;
    }
    /**
     * @return Property[]
     */
    private function getInjectProperties(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class) : array
    {
        return \array_filter($class->getProperties(), function (\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property $property) : bool {
            return $this->isInjectProperty($property);
        });
    }
    private function removeInjectAnnotation(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property $property) : void
    {
        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $property->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        $phpDocInfo->removeByName('inject');
    }
    private function changePropertyVisibility(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property $injectProperty) : void
    {
        if ($this->propertyUsageAnalyzer->isPropertyFetchedInChildClass($injectProperty)) {
            $this->makeProtected($injectProperty);
        } else {
            $this->makePrivate($injectProperty);
        }
    }
    private function isInjectProperty(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property $property) : bool
    {
        if (!$property->isPublic()) {
            return \false;
        }
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $property->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return \false;
        }
        return (bool) $phpDocInfo->getTagsByName('inject');
    }
}
