<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocInfo\PhpDocInfoPrinter;

use Iterator;
use PhpParser\Node\Stmt\Nop;
use Symplify\SmartFileSystem\SmartFileInfo;
final class PhpDocInfoPrinterTest extends \Rector\BetterPhpDocParser\Tests\PhpDocInfo\PhpDocInfoPrinter\AbstractPhpDocInfoPrinterTest
{
    /**
     * @dataProvider provideData()
     * @dataProvider provideDataCallable()
     */
    public function test(\Symplify\SmartFileSystem\SmartFileInfo $docFileInfo) : void
    {
        $this->doComparePrintedFileEquals($docFileInfo, $docFileInfo);
    }
    public function testRemoveSpace() : void
    {
        $this->doComparePrintedFileEquals(new \Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/FixtureChanged/with_space.txt'), new \Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/FixtureChangedExpected/with_space_expected.txt'));
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureBasic', '*.txt');
    }
    public function provideDataCallable() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureCallable', '*.txt');
    }
    /**
     * @dataProvider provideDataEmpty()
     */
    public function testEmpty(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $phpDocInfo = $this->createPhpDocInfoFromDocCommentAndNode($fileInfo->getContents(), new \PhpParser\Node\Stmt\Nop());
        $this->assertEmpty($this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo));
    }
    public function provideDataEmpty() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureEmpty', '*.txt');
    }
    private function doComparePrintedFileEquals(\Symplify\SmartFileSystem\SmartFileInfo $inputFileInfo, \Symplify\SmartFileSystem\SmartFileInfo $expectedFileInfo) : void
    {
        $phpDocInfo = $this->createPhpDocInfoFromDocCommentAndNode($inputFileInfo->getContents(), new \PhpParser\Node\Stmt\Nop());
        $printedDocComment = $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
        $this->assertSame($expectedFileInfo->getContents(), $printedDocComment, $inputFileInfo->getRelativeFilePathFromCwd());
    }
}