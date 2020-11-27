<?php

declare (strict_types=1);
namespace Rector\Restoration\Tests\Rector\Class_\RemoveUselessJustForSakeInterfaceRector;

use Iterator;
use Rector\Restoration\Rector\Class_\RemoveUselessJustForSakeInterfaceRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class RemoveUselessJustForSakeInterfaceRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\Restoration\Rector\Class_\RemoveUselessJustForSakeInterfaceRector::class;
    }
}