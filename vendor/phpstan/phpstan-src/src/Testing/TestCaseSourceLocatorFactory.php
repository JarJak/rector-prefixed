<?php

declare (strict_types=1);
namespace PHPStan\Testing;

use _PhpScoper006a73f0e455\Composer\Autoload\ClassLoader;
use PHPStan\DependencyInjection\Container;
use PHPStan\Reflection\BetterReflection\SourceLocator\AutoloadSourceLocator;
use PHPStan\Reflection\BetterReflection\SourceLocator\ComposerJsonAndInstalledJsonSourceLocatorMaker;
use PHPStan\Reflection\BetterReflection\SourceLocator\PhpVersionBlacklistSourceLocator;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\FunctionReflector;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Ast\Locator;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\SourceStubber\ReflectionSourceStubber;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\EvaledCodeSourceLocator;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\MemoizingSourceLocator;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\PhpInternalSourceLocator;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\SourceLocator;
class TestCaseSourceLocatorFactory
{
    /**
     * @var \PHPStan\DependencyInjection\Container
     */
    private $container;
    /**
     * @var \PHPStan\Reflection\BetterReflection\SourceLocator\ComposerJsonAndInstalledJsonSourceLocatorMaker
     */
    private $composerJsonAndInstalledJsonSourceLocatorMaker;
    /**
     * @var \PHPStan\Reflection\BetterReflection\SourceLocator\AutoloadSourceLocator
     */
    private $autoloadSourceLocator;
    /**
     * @var \PhpParser\Parser
     */
    private $phpParser;
    /**
     * @var \Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber
     */
    private $phpstormStubsSourceStubber;
    /**
     * @var \Roave\BetterReflection\SourceLocator\SourceStubber\ReflectionSourceStubber
     */
    private $reflectionSourceStubber;
    public function __construct(\PHPStan\DependencyInjection\Container $container, \PHPStan\Reflection\BetterReflection\SourceLocator\ComposerJsonAndInstalledJsonSourceLocatorMaker $composerJsonAndInstalledJsonSourceLocatorMaker, \PHPStan\Reflection\BetterReflection\SourceLocator\AutoloadSourceLocator $autoloadSourceLocator, \PhpParser\Parser $phpParser, \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber $phpstormStubsSourceStubber, \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\SourceStubber\ReflectionSourceStubber $reflectionSourceStubber)
    {
        $this->container = $container;
        $this->composerJsonAndInstalledJsonSourceLocatorMaker = $composerJsonAndInstalledJsonSourceLocatorMaker;
        $this->autoloadSourceLocator = $autoloadSourceLocator;
        $this->phpParser = $phpParser;
        $this->phpstormStubsSourceStubber = $phpstormStubsSourceStubber;
        $this->reflectionSourceStubber = $reflectionSourceStubber;
    }
    public function create() : \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\SourceLocator
    {
        $classLoaderReflection = new \ReflectionClass(\_PhpScoper006a73f0e455\Composer\Autoload\ClassLoader::class);
        if ($classLoaderReflection->getFileName() === \false) {
            throw new \PHPStan\ShouldNotHappenException('Unknown ClassLoader filename');
        }
        $composerProjectPath = \dirname($classLoaderReflection->getFileName(), 3);
        if (!\is_file($composerProjectPath . '/composer.json')) {
            throw new \PHPStan\ShouldNotHappenException(\sprintf('composer.json not found in directory %s', $composerProjectPath));
        }
        $composerSourceLocator = $this->composerJsonAndInstalledJsonSourceLocatorMaker->create($composerProjectPath);
        if ($composerSourceLocator === null) {
            throw new \PHPStan\ShouldNotHappenException('Could not create composer source locator');
        }
        $locators = [$composerSourceLocator];
        $astLocator = new \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Ast\Locator($this->phpParser, function () : FunctionReflector {
            return $this->container->getService('testCaseFunctionReflector');
        });
        $locators[] = new \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\PhpInternalSourceLocator($astLocator, $this->phpstormStubsSourceStubber);
        $locators[] = $this->autoloadSourceLocator;
        $locators[] = new \PHPStan\Reflection\BetterReflection\SourceLocator\PhpVersionBlacklistSourceLocator(new \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\PhpInternalSourceLocator($astLocator, $this->reflectionSourceStubber), $this->phpstormStubsSourceStubber);
        $locators[] = new \PHPStan\Reflection\BetterReflection\SourceLocator\PhpVersionBlacklistSourceLocator(new \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\EvaledCodeSourceLocator($astLocator, $this->reflectionSourceStubber), $this->phpstormStubsSourceStubber);
        return new \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\MemoizingSourceLocator(new \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator($locators));
    }
}
