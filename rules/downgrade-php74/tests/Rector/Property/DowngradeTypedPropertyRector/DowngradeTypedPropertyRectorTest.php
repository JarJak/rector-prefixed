<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\DowngradePhp74\Tests\Rector\Property\DowngradeTypedPropertyRector;

use Iterator;
use _PhpScoper0a2ac50786fa\Rector\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector;
use _PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo;
final class DowngradeTypedPropertyRectorTest extends \_PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @requires PHP 7.4
     * @dataProvider provideData()
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
        return \_PhpScoper0a2ac50786fa\Rector\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector::class;
    }
}
