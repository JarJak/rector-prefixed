<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Php80\Tests\Rector\Class_\AnnotationToAttributeRector;

use Iterator;
use _PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option;
use _PhpScoper0a2ac50786fa\Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use _PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo;
final class AutoImportedAnnotationToAttributeRectorTest extends \_PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\_PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->setParameter(\_PhpScoper0a2ac50786fa\Rector\Core\Configuration\Option::AUTO_IMPORT_NAMES, \true);
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureAutoImported');
    }
    protected function getRectorClass() : string
    {
        return \_PhpScoper0a2ac50786fa\Rector\Php80\Rector\Class_\AnnotationToAttributeRector::class;
    }
}
