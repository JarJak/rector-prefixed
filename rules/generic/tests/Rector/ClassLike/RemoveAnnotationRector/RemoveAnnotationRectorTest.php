<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Generic\Tests\Rector\ClassLike\RemoveAnnotationRector;

use Iterator;
use _PhpScoper0a2ac50786fa\Rector\Generic\Rector\ClassLike\RemoveAnnotationRector;
use _PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo;
final class RemoveAnnotationRectorTest extends \_PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase
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
    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration() : array
    {
        return [\_PhpScoper0a2ac50786fa\Rector\Generic\Rector\ClassLike\RemoveAnnotationRector::class => [\_PhpScoper0a2ac50786fa\Rector\Generic\Rector\ClassLike\RemoveAnnotationRector::ANNOTATIONS_TO_REMOVE => ['method']]];
    }
}
