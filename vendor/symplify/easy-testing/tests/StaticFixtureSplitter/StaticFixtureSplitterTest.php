<?php

declare (strict_types=1);
namespace RectorPrefix20210118\Symplify\EasyTesting\Tests\StaticFixtureSplitter;

use RectorPrefix20210118\PHPUnit\Framework\TestCase;
use RectorPrefix20210118\Symplify\EasyTesting\StaticFixtureSplitter;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
final class StaticFixtureSplitterTest extends \RectorPrefix20210118\PHPUnit\Framework\TestCase
{
    public function test() : void
    {
        $fileInfo = new \RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/Source/simple_fixture.php.inc');
        $inputAndExpected = \RectorPrefix20210118\Symplify\EasyTesting\StaticFixtureSplitter::splitFileInfoToInputAndExpected($fileInfo);
        $this->assertSame('a' . \PHP_EOL, $inputAndExpected->getInput());
        $this->assertSame('b' . \PHP_EOL, $inputAndExpected->getExpected());
    }
    public function testSplitFileInfoToLocalInputAndExpected() : void
    {
        $fileInfo = new \RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/Source/file_and_value.php.inc');
        $inputFileInfoAndExpected = \RectorPrefix20210118\Symplify\EasyTesting\StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($fileInfo);
        $inputFileRealPath = $inputFileInfoAndExpected->getInputFileRealPath();
        $this->assertFileExists($inputFileRealPath);
        $this->assertSame(15025, $inputFileInfoAndExpected->getExpected());
    }
}
