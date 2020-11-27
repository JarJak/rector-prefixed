<?php

declare (strict_types=1);
namespace Rector\SOLID\Tests\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;

use Iterator;
use Rector\SOLID\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class ChangeIfElseValueAssignToEarlyReturnRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return \Rector\SOLID\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector::class;
    }
}
