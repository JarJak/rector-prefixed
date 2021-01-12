<?php

namespace Rector\Composer\Tests\Modifier;

use RectorPrefix20210112\Nette\Utils\Json;
use Rector\Composer\ValueObject\ComposerModifier\AddPackageToRequire;
use Rector\Composer\ValueObject\ComposerModifier\ReplacePackage;
use Rector\Composer\ValueObject\ComposerModifier\ChangePackageVersion;
use Rector\Composer\ValueObject\ComposerModifier\MovePackageToRequireDev;
use Rector\Composer\ValueObject\ComposerModifier\RemovePackage;
use Rector\Composer\Modifier\ComposerModifier;
use Rector\Core\HttpKernel\RectorKernel;
use RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use RectorPrefix20210112\Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
final class ComposerModifierTest extends \RectorPrefix20210112\Symplify\PackageBuilder\Testing\AbstractKernelTestCase
{
    protected function setUp() : void
    {
        $this->bootKernelWithConfigs(\Rector\Core\HttpKernel\RectorKernel::class, []);
    }
    public function testRefactorWithOneAddedPackage() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\AddPackageToRequire('vendor1/package3', '^3.0')]);
        $composerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0']);
        $changedComposerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $changedComposerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0', 'vendor1/package3' => '^3.0']);
        $this->assertEquals($changedComposerJson, $composerModifier->modify($composerJson));
    }
    public function testRefactorWithOneAddedAndOneRemovedPackage() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\AddPackageToRequire('vendor1/package3', '^3.0'), new \Rector\Composer\ValueObject\ComposerModifier\RemovePackage('vendor1/package1')]);
        $composerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0']);
        $changedComposerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $changedComposerJson->setRequire(['vendor1/package2' => '^2.0', 'vendor1/package3' => '^3.0']);
        $this->assertEquals($changedComposerJson, $composerModifier->modify($composerJson));
    }
    public function testRefactorWithAddedAndRemovedSamePackage() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\AddPackageToRequire('vendor1/package3', '^3.0'), new \Rector\Composer\ValueObject\ComposerModifier\RemovePackage('vendor1/package3')]);
        $composerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0']);
        $changedComposerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $changedComposerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0']);
        $this->assertEquals($changedComposerJson, $composerModifier->modify($composerJson));
    }
    public function testRefactorWithRemovedAndAddedBackSamePackage() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\RemovePackage('vendor1/package3'), new \Rector\Composer\ValueObject\ComposerModifier\AddPackageToRequire('vendor1/package3', '^3.0')]);
        $composerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0']);
        $changedComposerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $changedComposerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0', 'vendor1/package3' => '^3.0']);
        $this->assertEquals($changedComposerJson, $composerModifier->modify($composerJson));
    }
    public function testRefactorWithMovedAndChangedPackages() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\MovePackageToRequireDev('vendor1/package1'), new \Rector\Composer\ValueObject\ComposerModifier\ReplacePackage('vendor1/package2', 'vendor2/package1', '^3.0'), new \Rector\Composer\ValueObject\ComposerModifier\ChangePackageVersion('vendor1/package3', '~3.0.0')]);
        $composerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0', 'vendor1/package3' => '^3.0']);
        $changedComposerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $changedComposerJson->setRequire(['vendor1/package3' => '~3.0.0', 'vendor2/package1' => '^3.0']);
        $changedComposerJson->setRequireDev(['vendor1/package1' => '^1.0']);
        $this->assertEquals($changedComposerJson, $composerModifier->modify($composerJson));
    }
    public function testRefactorWithMultipleConfiguration() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\MovePackageToRequireDev('vendor1/package1')]);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\ReplacePackage('vendor1/package2', 'vendor2/package1', '^3.0')]);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\ChangePackageVersion('vendor1/package3', '~3.0.0')]);
        $composerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0', 'vendor1/package3' => '^3.0']);
        $changedComposerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $changedComposerJson->setRequire(['vendor1/package3' => '~3.0.0', 'vendor2/package1' => '^3.0']);
        $changedComposerJson->setRequireDev(['vendor1/package1' => '^1.0']);
        $this->assertEquals($changedComposerJson, $composerModifier->modify($composerJson));
    }
    public function testRefactorWithConfigurationAndReconfigurationAndConfiguration() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\MovePackageToRequireDev('vendor1/package1')]);
        $composerModifier->reconfigure([new \Rector\Composer\ValueObject\ComposerModifier\ReplacePackage('vendor1/package2', 'vendor2/package1', '^3.0')]);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\ChangePackageVersion('vendor1/package3', '~3.0.0')]);
        $composerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0', 'vendor1/package3' => '^3.0']);
        $changedComposerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $changedComposerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package3' => '~3.0.0', 'vendor2/package1' => '^3.0']);
        $this->assertEquals($changedComposerJson, $composerModifier->modify($composerJson));
    }
    public function testRefactorWithMovedAndChangedPackagesWithSortPackages() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $composerModifier->configure([new \Rector\Composer\ValueObject\ComposerModifier\MovePackageToRequireDev('vendor1/package1'), new \Rector\Composer\ValueObject\ComposerModifier\ReplacePackage('vendor1/package2', 'vendor1/package0', '^3.0'), new \Rector\Composer\ValueObject\ComposerModifier\ChangePackageVersion('vendor1/package3', '~3.0.0')]);
        $composerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $composerJson->setConfig(['sort-packages' => \true]);
        $composerJson->setRequire(['vendor1/package1' => '^1.0', 'vendor1/package2' => '^2.0', 'vendor1/package3' => '^3.0']);
        $changedComposerJson = new \RectorPrefix20210112\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson();
        $changedComposerJson->setConfig(['sort-packages' => \true]);
        $changedComposerJson->setRequire(['vendor1/package0' => '^3.0', 'vendor1/package3' => '~3.0.0']);
        $changedComposerJson->setRequireDev(['vendor1/package1' => '^1.0']);
        $this->assertEquals($changedComposerJson, $composerModifier->modify($composerJson));
    }
    public function testFilePath() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $this->assertEquals(\getcwd() . '/composer.json', $composerModifier->getFilePath());
        $composerModifier->filePath('test/composer.json');
        $this->assertEquals('test/composer.json', $composerModifier->getFilePath());
    }
    public function testCommand() : void
    {
        /** @var ComposerModifier $composerModifier */
        $composerModifier = $this->getService(\Rector\Composer\Modifier\ComposerModifier::class);
        $this->assertEquals('composer update', $composerModifier->getCommand());
        $composerModifier->command('composer update --prefer-stable');
        $this->assertEquals('composer update --prefer-stable', $composerModifier->getCommand());
    }
}
