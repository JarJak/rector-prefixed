<?php

declare (strict_types=1);
namespace Rector\NetteCodeQuality\Tests\Rector\ArrayDimFetch\ChangeFormArrayAccessToAnnotatedControlVariableRector;

use Iterator;
use Rector\NetteCodeQuality\Rector\ArrayDimFetch\ChangeFormArrayAccessToAnnotatedControlVariableRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
final class ChangeFormArrayAccessToAnnotatedControlVariableRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return \Rector\NetteCodeQuality\Rector\ArrayDimFetch\ChangeFormArrayAccessToAnnotatedControlVariableRector::class;
    }
}
