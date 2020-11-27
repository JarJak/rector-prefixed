<?php

declare (strict_types=1);
namespace Rector\DowngradePhp80\Tests\Rector\FunctionLike\DowngradeParamMixedTypeDeclarationRector;

use Iterator;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeParamMixedTypeDeclarationRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class DowngradeParamMixedTypeDeclarationRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @requires PHP >= 8.0
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
        return [\Rector\DowngradePhp80\Rector\FunctionLike\DowngradeParamMixedTypeDeclarationRector::class => [\Rector\DowngradePhp80\Rector\FunctionLike\DowngradeParamMixedTypeDeclarationRector::ADD_DOC_BLOCK => \true]];
    }
    protected function getRectorClass() : string
    {
        return \Rector\DowngradePhp80\Rector\FunctionLike\DowngradeParamMixedTypeDeclarationRector::class;
    }
    protected function getPhpVersion() : int
    {
        return \Rector\Core\ValueObject\PhpVersionFeature::MIXED_TYPE - 1;
    }
}
