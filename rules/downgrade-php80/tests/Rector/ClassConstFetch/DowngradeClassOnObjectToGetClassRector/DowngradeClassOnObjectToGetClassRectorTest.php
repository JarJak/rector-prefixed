<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\DowngradePhp80\Tests\Rector\ClassConstFetch\DowngradeClassOnObjectToGetClassRector;

use Iterator;
use _PhpScoper0a2ac50786fa\Rector\DowngradePhp80\Rector\ClassConstFetch\DowngradeClassOnObjectToGetClassRector;
use _PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo;
final class DowngradeClassOnObjectToGetClassRectorTest extends \_PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     * @requires PHP 8.0
     */
    public function test(\_PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    protected function getRectorClass() : string
    {
        return \_PhpScoper0a2ac50786fa\Rector\DowngradePhp80\Rector\ClassConstFetch\DowngradeClassOnObjectToGetClassRector::class;
    }
}
