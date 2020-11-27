<?php

declare (strict_types=1);
namespace Rector\Transform\Tests\Rector\StaticCall\StaticCallToMethodCallRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector;
use Rector\Transform\ValueObject\StaticCallToMethodCall;
use Symplify\SmartFileSystem\SmartFileInfo;
final class StaticCallToMethodCallRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
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
        return [\Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector::class => [\Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector::STATIC_CALLS_TO_METHOD_CALLS => [new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoper006a73f0e455\\Nette\\Utils\\FileSystem', 'write', 'Symplify\\SmartFileSystem\\SmartFileSystem', 'dumpFile'), new \Rector\Transform\ValueObject\StaticCallToMethodCall('_PhpScoper006a73f0e455\\Illuminate\\Support\\Facades\\Response', '*', '_PhpScoper006a73f0e455\\Illuminate\\Contracts\\Routing\\ResponseFactory', '*')]]];
    }
}