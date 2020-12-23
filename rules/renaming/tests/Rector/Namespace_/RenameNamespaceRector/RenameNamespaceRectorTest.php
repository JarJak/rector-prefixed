<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Renaming\Tests\Rector\Namespace_\RenameNamespaceRector;

use Iterator;
use _PhpScoper0a2ac50786fa\Rector\Renaming\Rector\Namespace_\RenameNamespaceRector;
use _PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo;
final class RenameNamespaceRectorTest extends \_PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\AbstractRectorTestCase
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
        return [\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\Namespace_\RenameNamespaceRector::class => [\_PhpScoper0a2ac50786fa\Rector\Renaming\Rector\Namespace_\RenameNamespaceRector::OLD_TO_NEW_NAMESPACES => ['OldNamespace' => 'NewNamespace', '_PhpScoper0a2ac50786fa\\OldNamespaceWith\\OldSplitNamespace' => '_PhpScoper0a2ac50786fa\\NewNamespaceWith\\NewSplitNamespace', '_PhpScoper0a2ac50786fa\\Old\\Long\\AnyNamespace' => '_PhpScoper0a2ac50786fa\\Short\\AnyNamespace', 'PHPUnit_Framework_' => 'PHPUnit\\Framework\\']]];
    }
}
