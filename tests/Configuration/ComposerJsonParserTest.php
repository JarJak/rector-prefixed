<?php

declare (strict_types=1);
namespace Rector\Core\Tests\Configuration;

use Iterator;
use _PhpScoper006a73f0e455\Nette\Utils\Json;
use Rector\Core\Configuration\MinimalVersionChecker\ComposerJsonParser;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
final class ComposerJsonParserTest extends \Symplify\PackageBuilder\Testing\AbstractKernelTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function test(string $expectedVersion, string $version) : void
    {
        $actualPhpVersion = $this->getComposerJsonPhpVersion($version);
        $this->assertSame($expectedVersion, $actualPhpVersion);
    }
    public function dataProvider() : \Iterator
    {
        (yield ['7.2.0', '7.2.0']);
        (yield ['7.2.0', '~7.2.0']);
        (yield ['7.2', '7.2.*']);
        (yield ['7', '7.*.*']);
        (yield ['7.2.0', '~7.2.0']);
        (yield ['7.2.0', '^7.2.0']);
        (yield ['7.2.0', '>=7.2.0']);
    }
    private function getComposerJsonPhpVersion(string $version) : string
    {
        $composerJsonParser = new \Rector\Core\Configuration\MinimalVersionChecker\ComposerJsonParser(\_PhpScoper006a73f0e455\Nette\Utils\Json::encode(['require' => ['php' => $version]]));
        return $composerJsonParser->getPhpVersion();
    }
}
