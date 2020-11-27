<?php

declare (strict_types=1);
namespace Rector\StrictCodeQuality\Tests\Rector\Stmt\VarInlineAnnotationToAssertRector;

use Iterator;
use Rector\StrictCodeQuality\Rector\Stmt\VarInlineAnnotationToAssertRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class VarInlineAnnotationToAssertRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return \Rector\StrictCodeQuality\Rector\Stmt\VarInlineAnnotationToAssertRector::class;
    }
}