<?php

declare (strict_types=1);
namespace Rector\DependencyInjection\Tests\Rector\Class_\MultiParentingToAbstractDependencyRector;

use Iterator;
use Rector\DependencyInjection\Rector\Class_\MultiParentingToAbstractDependencyRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use RectorPrefix20210104\Symplify\SmartFileSystem\SmartFileInfo;
final class SymfonyMultiParentingToAbstractDependencyRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\RectorPrefix20210104\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/FixtureSymfony');
    }
    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration() : array
    {
        return [\Rector\DependencyInjection\Rector\Class_\MultiParentingToAbstractDependencyRector::class => [\Rector\DependencyInjection\Rector\Class_\MultiParentingToAbstractDependencyRector::FRAMEWORK => 'symfony']];
    }
}
