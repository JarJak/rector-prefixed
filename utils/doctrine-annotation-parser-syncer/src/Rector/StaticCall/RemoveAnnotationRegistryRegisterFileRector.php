<?php

declare (strict_types=1);
namespace Rector\Utils\DoctrineAnnotationParserSyncer\Rector\StaticCall;

use _PhpScopera143bcca66cb\Doctrine\Common\Annotations\AnnotationReader;
use _PhpScopera143bcca66cb\Doctrine\Common\Annotations\AnnotationRegistry;
use _PhpScopera143bcca66cb\Doctrine\Common\Annotations\DocParser;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Utils\DoctrineAnnotationParserSyncer\Contract\Rector\ClassSyncerRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
final class RemoveAnnotationRegistryRegisterFileRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Utils\DoctrineAnnotationParserSyncer\Contract\Rector\ClassSyncerRectorInterface
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove AnnotationRegistry::registerFile() that is now covered by composer autoload', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
AnnotationRegistry::registerFile()
CODE_SAMPLE
, <<<'CODE_SAMPLE'
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param StaticCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isInClassesNamed($node, [\_PhpScopera143bcca66cb\Doctrine\Common\Annotations\DocParser::class, \_PhpScopera143bcca66cb\Doctrine\Common\Annotations\AnnotationReader::class])) {
            return null;
        }
        if (!$this->isStaticCallNamed($node, \_PhpScopera143bcca66cb\Doctrine\Common\Annotations\AnnotationRegistry::class, 'registerFile')) {
            return null;
        }
        $this->removeNode($node);
        return $node;
    }
}
