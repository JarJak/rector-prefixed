<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Restoration\Tests\Rector\Namespace_\CompleteImportForPartialAnnotationRector;

use Iterator;
use _PhpScoper0a2ac50786fa\Rector\Restoration\Rector\Namespace_\CompleteImportForPartialAnnotationRector;
use _PhpScoper0a2ac50786fa\Rector\Restoration\ValueObject\UseWithAlias;
use _PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo;
final class CompleteImportForPartialAnnotationRectorTest extends \_PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return [\_PhpScoper0a2ac50786fa\Rector\Restoration\Rector\Namespace_\CompleteImportForPartialAnnotationRector::class => [\_PhpScoper0a2ac50786fa\Rector\Restoration\Rector\Namespace_\CompleteImportForPartialAnnotationRector::USE_IMPORTS_TO_RESTORE => [new \_PhpScoper0a2ac50786fa\Rector\Restoration\ValueObject\UseWithAlias('_PhpScoper0a2ac50786fa\\Doctrine\\ORM\\Mapping', 'ORM')]]];
    }
}
