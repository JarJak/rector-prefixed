<?php

declare (strict_types=1);
namespace Rector\Testing\PHPUnit\Behavior;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\FileSystemRector\Contract\MovedFileInterface;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Symplify\SmartFileSystem\SmartFileInfo;
use _PhpScopera143bcca66cb\Webmozart\Assert\Assert;
/**
 * @property-read RemovedAndAddedFilesCollector $removedAndAddedFilesCollector
 */
trait MovingFilesTrait
{
    protected function matchMovedFile(\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : \Rector\FileSystemRector\Contract\MovedFileInterface
    {
        return $this->removedAndAddedFilesCollector->getMovedFileByFileInfo($smartFileInfo);
    }
    protected function assertFileWasNotChanged(\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        $movedFile = $this->removedAndAddedFilesCollector->getMovedFileByFileInfo($smartFileInfo);
        $this->assertNull($movedFile);
    }
    protected function assertFileWithContentWasAdded(\Rector\FileSystemRector\ValueObject\AddedFileWithContent $addedFileWithContent) : void
    {
        $this->assertFilesWereAdded([$addedFileWithContent]);
    }
    protected function assertFileWasRemoved(\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        $isFileRemoved = $this->removedAndAddedFilesCollector->isFileRemoved($smartFileInfo);
        $this->assertTrue($isFileRemoved);
    }
    /**
     * @param AddedFileWithContent[] $addedFileWithContents
     */
    protected function assertFilesWereAdded(array $addedFileWithContents) : void
    {
        \_PhpScopera143bcca66cb\Webmozart\Assert\Assert::allIsAOf($addedFileWithContents, \Rector\FileSystemRector\ValueObject\AddedFileWithContent::class);
        $addedFilePathsWithContents = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();
        \sort($addedFilePathsWithContents);
        \sort($addedFileWithContents);
        foreach ($addedFilePathsWithContents as $key => $addedFilePathWithContent) {
            $expectedFilePathWithContent = $addedFileWithContents[$key];
            $this->assertSame($expectedFilePathWithContent->getFilePath(), $addedFilePathWithContent->getFilePath());
            $this->assertSame($expectedFilePathWithContent->getFileContent(), $addedFilePathWithContent->getFileContent());
        }
    }
}
