<?php

declare (strict_types=1);
namespace Rector\Utils\DoctrineAnnotationParserSyncer\FileSyncer;

use PhpParser\Node;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\Utils\DoctrineAnnotationParserSyncer\Contract\ClassSyncerInterface;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;
abstract class AbstractClassSyncer implements \Rector\Utils\DoctrineAnnotationParserSyncer\Contract\ClassSyncerInterface
{
    /**
     * @var SmartFileSystem
     */
    protected $smartFileSystem;
    /**
     * @var BetterStandardPrinter
     */
    private $betterStandardPrinter;
    /**
     * @var FileInfoParser
     */
    private $fileInfoParser;
    /**
     * @required
     */
    public function autowireAbstractClassSyncer(\Rector\Core\PhpParser\Printer\BetterStandardPrinter $betterStandardPrinter, \Rector\FileSystemRector\Parser\FileInfoParser $fileInfoParser, \Symplify\SmartFileSystem\SmartFileSystem $smartFileSystem) : void
    {
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->fileInfoParser = $fileInfoParser;
        $this->smartFileSystem = $smartFileSystem;
    }
    /**
     * @return Node[]
     */
    protected function getFileNodes() : array
    {
        $docParserFileInfo = new \Symplify\SmartFileSystem\SmartFileInfo($this->getSourceFilePath());
        return $this->fileInfoParser->parseFileInfoToNodesAndDecorate($docParserFileInfo);
    }
    /**
     * @param Node[] $nodes
     */
    protected function printNodesToPath(array $nodes) : void
    {
        $printedContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
        $this->smartFileSystem->dumpFile($this->getTargetFilePath(), $printedContent);
    }
    /**
     * @param Node[] $nodes
     */
    protected function hasContentChanged(array $nodes) : bool
    {
        $finalContent = $this->betterStandardPrinter->prettyPrintFile($nodes);
        // nothing to validate against
        if (!\file_exists($this->getTargetFilePath())) {
            return \false;
        }
        $currentContent = $this->smartFileSystem->readFile($this->getTargetFilePath());
        // has content changed
        return $finalContent !== $currentContent;
    }
}