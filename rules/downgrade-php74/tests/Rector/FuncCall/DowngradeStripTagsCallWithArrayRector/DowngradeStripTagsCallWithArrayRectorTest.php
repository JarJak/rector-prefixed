<?php

declare (strict_types=1);
namespace Rector\DowngradePhp74\Tests\Rector\FuncCall\DowngradeStripTagsCallWithArrayRector;

use Iterator;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DowngradePhp74\Rector\FuncCall\DowngradeStripTagsCallWithArrayRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class DowngradeStripTagsCallWithArrayRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @requires PHP 7.4
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
        return \Rector\DowngradePhp74\Rector\FuncCall\DowngradeStripTagsCallWithArrayRector::class;
    }
    protected function getPhpVersion() : int
    {
        return \Rector\Core\ValueObject\PhpVersionFeature::STRIP_TAGS_WITH_ARRAY - 1;
    }
}