<?php

declare (strict_types=1);
namespace Rector\Symfony3\Tests\Rector\ClassMethod\MergeMethodAnnotationToRouteAnnotationRector;

use Iterator;
use Rector\Symfony3\Rector\ClassMethod\MergeMethodAnnotationToRouteAnnotationRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210108\Symplify\SmartFileSystem\SmartFileInfo;
final class MergeMethodAnnotationToRouteAnnotationRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210108\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \Rector\Symfony3\Rector\ClassMethod\MergeMethodAnnotationToRouteAnnotationRector::class;
    }
}
