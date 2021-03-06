<?php

declare (strict_types=1);
namespace Rector\PSR4\Rector\Namespace_;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\MovedFileWithNodes;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PSR4\FileInfoAnalyzer\FileInfoDeletionAnalyzer;
use Rector\PSR4\NodeManipulator\NamespaceManipulator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Rector\PSR4\Tests\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector\MultipleClassFileToPsr4ClassesRectorTest
 */
final class MultipleClassFileToPsr4ClassesRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var NamespaceManipulator
     */
    private $namespaceManipulator;
    /**
     * @var FileInfoDeletionAnalyzer
     */
    private $fileInfoDeletionAnalyzer;
    public function __construct(\Rector\PSR4\NodeManipulator\NamespaceManipulator $namespaceManipulator, \Rector\PSR4\FileInfoAnalyzer\FileInfoDeletionAnalyzer $fileInfoDeletionAnalyzer)
    {
        $this->namespaceManipulator = $namespaceManipulator;
        $this->fileInfoDeletionAnalyzer = $fileInfoDeletionAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change multiple classes in one file to standalone PSR-4 classes.', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
namespace App\Exceptions;

use Exception;

final class FirstException extends Exception
{
}

final class SecondException extends Exception
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
// new file: "app/Exceptions/FirstException.php"
namespace App\Exceptions;

use Exception;

final class FirstException extends Exception
{
}

// new file: "app/Exceptions/SecondException.php"
namespace App\Exceptions;

use Exception;

final class SecondException extends Exception
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
        return [\PhpParser\Node\Stmt\Namespace_::class, \Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace::class];
    }
    /**
     * @param Namespace_|FileWithoutNamespace $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->hasAtLeastTwoClassLikes($node)) {
            return null;
        }
        $nodeToReturn = null;
        if ($node instanceof \PhpParser\Node\Stmt\Namespace_) {
            $nodeToReturn = $this->refactorNamespace($node);
        }
        if ($node instanceof \Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace) {
            $nodeToReturn = $this->refactorFileWithoutNamespace($node);
        }
        // 1. remove this node
        if ($nodeToReturn !== null) {
            return $nodeToReturn;
        }
        /** @var SmartFileInfo $smartFileInfo */
        $smartFileInfo = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::FILE_INFO);
        // 2. nothing to return - remove the file
        $this->removeFile($smartFileInfo);
        return null;
    }
    private function hasAtLeastTwoClassLikes(\PhpParser\Node $node) : bool
    {
        $classes = $this->betterNodeFinder->findClassLikes($node);
        return \count($classes) > 1;
    }
    private function refactorNamespace(\PhpParser\Node\Stmt\Namespace_ $namespace) : ?\PhpParser\Node\Stmt\Namespace_
    {
        /** @var ClassLike[] $classLikes */
        $classLikes = $this->betterNodeFinder->findClassLikes($namespace->stmts);
        $emptyNamespace = $this->namespaceManipulator->removeClassLikes($namespace);
        $nodeToReturn = null;
        foreach ($classLikes as $classLike) {
            $newNamespace = clone $emptyNamespace;
            $newNamespace->stmts[] = $classLike;
            // 1. is the class that will be kept in original file?
            if ($this->fileInfoDeletionAnalyzer->isClassLikeAndFileInfoMatch($classLike)) {
                $nodeToReturn = $newNamespace;
                continue;
            }
            // 2. new file
            $this->printNewNodes($classLike, $newNamespace);
        }
        return $nodeToReturn;
    }
    private function refactorFileWithoutNamespace(\Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace $fileWithoutNamespace) : ?\Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace
    {
        /** @var ClassLike[] $classLikes */
        $classLikes = $this->betterNodeFinder->findClassLikes($fileWithoutNamespace->stmts);
        $nodeToReturn = null;
        foreach ($classLikes as $classLike) {
            // 1. is the class that will be kept in original file?
            if ($this->fileInfoDeletionAnalyzer->isClassLikeAndFileInfoMatch($classLike)) {
                $nodeToReturn = $fileWithoutNamespace;
                continue;
            }
            // 2. is new file
            $this->printNewNodes($classLike, $fileWithoutNamespace);
        }
        return $nodeToReturn;
    }
    /**
     * @param Namespace_|FileWithoutNamespace $mainNode
     */
    private function printNewNodes(\PhpParser\Node\Stmt\ClassLike $classLike, \PhpParser\Node $mainNode) : void
    {
        /** @var SmartFileInfo $smartFileInfo */
        $smartFileInfo = $mainNode->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::FILE_INFO);
        /** @var Declare_[] $declares */
        $declares = (array) $mainNode->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::DECLARES);
        if ($mainNode instanceof \Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace) {
            $nodesToPrint = \array_merge($declares, [$classLike]);
        } else {
            $nodesToPrint = \array_merge($declares, [$mainNode]);
        }
        $fileDestination = $this->createClassLikeFileDestination($classLike, $smartFileInfo);
        $movedFileWithNodes = new \Rector\FileSystemRector\ValueObject\MovedFileWithNodes($nodesToPrint, $fileDestination, $smartFileInfo);
        $this->addMovedFile($movedFileWithNodes);
    }
    private function createClassLikeFileDestination(\PhpParser\Node\Stmt\ClassLike $classLike, \RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : string
    {
        $currentDirectory = \dirname($smartFileInfo->getRealPath());
        return $currentDirectory . \DIRECTORY_SEPARATOR . $classLike->name . '.php';
    }
}
