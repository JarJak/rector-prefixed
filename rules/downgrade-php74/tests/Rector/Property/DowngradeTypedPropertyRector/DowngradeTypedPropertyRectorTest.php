<?php

declare (strict_types=1);
namespace Rector\DowngradePhp74\Tests\Rector\Property\DowngradeTypedPropertyRector;

use Iterator;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class DowngradeTypedPropertyRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
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
    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration() : array
    {
        return [\Rector\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector::class => [\Rector\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector::ADD_DOC_BLOCK => \true]];
    }
    protected function getRectorClass() : string
    {
        return \Rector\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector::class;
    }
    protected function getPhpVersion() : int
    {
        return \Rector\Core\ValueObject\PhpVersionFeature::TYPED_PROPERTIES - 1;
    }
}
