<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Autodiscovery\Rector\FileNode;

use _PhpScoper0a2ac50786fa\Nette\Utils\Strings;
use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\CustomNode\FileNode;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @sponsor Thanks https://spaceflow.io/ for sponsoring this rule - visit them on https://github.com/SpaceFlow-app
 *
 * Inspiration @see https://github.com/rectorphp/rector/pull/1865/files#diff-0d18e660cdb626958662641b491623f8
 *
 * @see \Rector\Autodiscovery\Tests\Rector\FileNode\MoveEntitiesToEntityDirectoryRector\MoveEntitiesToEntityDirectoryRectorTest
 */
final class MoveEntitiesToEntityDirectoryRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/auSMk3/1
     */
    private const ENTITY_PATH_REGEX = '#\\bEntity\\b#';
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Move entities to Entity namespace', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
// file: app/Controller/Product.php

namespace App\Controller;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Product
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
// file: app/Entity/Product.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Product
{
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\CustomNode\FileNode::class];
    }
    /**
     * @param FileNode $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        if (!$this->isDoctrineEntityFileNode($node)) {
            return null;
        }
        // is entity in expected directory?
        $smartFileInfo = $node->getFileInfo();
        if (\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::match($smartFileInfo->getRealPath(), self::ENTITY_PATH_REGEX)) {
            return null;
        }
        $movedFileWithNodes = $this->movedFileWithNodesFactory->createWithDesiredGroup($smartFileInfo, $node->stmts, 'Entity');
        if ($movedFileWithNodes === null) {
            return null;
        }
        $this->addMovedFile($movedFileWithNodes);
        return null;
    }
    private function isDoctrineEntityFileNode(\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\CustomNode\FileNode $fileNode) : bool
    {
        /** @var Class_|null $class */
        $class = $this->betterNodeFinder->findFirstInstanceOf($fileNode->stmts, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_::class);
        if ($class === null) {
            return \false;
        }
        return $this->isDoctrineEntityClass($class);
    }
}
