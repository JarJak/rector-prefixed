<?php

declare (strict_types=1);
namespace Rector\Nette\Tests\Rector\MethodCall\BuilderExpandToHelperExpandRector;

use Iterator;
use Rector\Nette\Rector\MethodCall\BuilderExpandToHelperExpandRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class BuilderExpandToHelperExpandRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return \Rector\Nette\Rector\MethodCall\BuilderExpandToHelperExpandRector::class;
    }
}
