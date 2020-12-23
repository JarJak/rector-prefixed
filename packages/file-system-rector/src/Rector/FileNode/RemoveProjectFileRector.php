<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\FileSystemRector\Rector\FileNode;

use _PhpScoper0a2ac50786fa\Nette\Utils\Strings;
use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\CustomNode\FileNode;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use _PhpScoper0a2ac50786fa\Webmozart\Assert\Assert;
/**
 * @see \Rector\FileSystemRector\Tests\Rector\FileNode\RemoveProjectFileRector\RemoveProjectFileRectorTest
 */
final class RemoveProjectFileRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector implements \_PhpScoper0a2ac50786fa\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const FILE_PATHS_TO_REMOVE = 'file_paths_to_remove';
    /**
     * @var string[]
     */
    private $filePathsToRemove = [];
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove file relative to project directory', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
// someFile/ToBeRemoved.txt
CODE_SAMPLE
, <<<'CODE_SAMPLE'
CODE_SAMPLE
, [self::FILE_PATHS_TO_REMOVE => ['someFile/ToBeRemoved.txt']])]);
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
        if ($this->filePathsToRemove === []) {
            return null;
        }
        $projectDirectory = \getcwd();
        $smartFileInfo = $node->getFileInfo();
        $relativePathInProject = $smartFileInfo->getRelativeFilePathFromDirectory($projectDirectory);
        foreach ($this->filePathsToRemove as $filePathsToRemove) {
            if (!$this->isFilePathToRemove($relativePathInProject, $filePathsToRemove)) {
                continue;
            }
            $this->removeFile($smartFileInfo);
        }
        return null;
    }
    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration) : void
    {
        $filePathsToRemove = $configuration[self::FILE_PATHS_TO_REMOVE] ?? [];
        \_PhpScoper0a2ac50786fa\Webmozart\Assert\Assert::allString($filePathsToRemove);
        $this->filePathsToRemove = $filePathsToRemove;
    }
    private function isFilePathToRemove(string $relativePathInProject, string $filePathToRemove) : bool
    {
        if (\_PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\StaticPHPUnitEnvironment::isPHPUnitRun() && \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::endsWith($relativePathInProject, $filePathToRemove)) {
            // only for tests
            return \true;
        }
        return $relativePathInProject === $filePathToRemove;
    }
}
