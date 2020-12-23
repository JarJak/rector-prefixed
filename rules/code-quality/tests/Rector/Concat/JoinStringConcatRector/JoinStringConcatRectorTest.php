<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\CodeQuality\Tests\Rector\Concat\JoinStringConcatRector;

use Iterator;
use _PhpScoper0a2ac50786fa\Rector\CodeQuality\Rector\Concat\JoinStringConcatRector;
use _PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo;
final class JoinStringConcatRectorTest extends \_PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
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
        return \_PhpScoper0a2ac50786fa\Rector\CodeQuality\Rector\Concat\JoinStringConcatRector::class;
    }
}
