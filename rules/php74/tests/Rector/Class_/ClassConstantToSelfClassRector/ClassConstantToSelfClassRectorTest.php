<?php

declare (strict_types=1);
namespace Rector\Php74\Tests\Rector\Class_\ClassConstantToSelfClassRector;

use Iterator;
use Rector\Php74\Rector\Class_\ClassConstantToSelfClassRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
final class ClassConstantToSelfClassRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return \Rector\Php74\Rector\Class_\ClassConstantToSelfClassRector::class;
    }
}
