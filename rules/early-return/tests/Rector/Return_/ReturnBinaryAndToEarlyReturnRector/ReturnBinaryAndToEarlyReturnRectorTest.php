<?php

declare (strict_types=1);
namespace Rector\EarlyReturn\Tests\Rector\Return_\ReturnBinaryAndToEarlyReturnRector;

use Iterator;
use Rector\EarlyReturn\Rector\Return_\ReturnBinaryAndToEarlyReturnRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
final class ReturnBinaryAndToEarlyReturnRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\EarlyReturn\Rector\Return_\ReturnBinaryAndToEarlyReturnRector::class;
    }
}
