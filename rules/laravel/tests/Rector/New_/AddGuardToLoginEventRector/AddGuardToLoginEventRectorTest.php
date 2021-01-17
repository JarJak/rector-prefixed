<?php

declare (strict_types=1);
namespace Rector\Laravel\Tests\Rector\New_\AddGuardToLoginEventRector;

use Iterator;
use Rector\Laravel\Rector\New_\AddGuardToLoginEventRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210117\Symplify\SmartFileSystem\SmartFileInfo;
final class AddGuardToLoginEventRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210117\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\Laravel\Rector\New_\AddGuardToLoginEventRector::class;
    }
}
