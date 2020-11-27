<?php

declare (strict_types=1);
namespace PHPStan\DependencyInjection;

use _PhpScoper006a73f0e455\Nette\DI\Extensions\PhpExtension;
use Phar;
use PHPStan\Broker\Broker;
use PHPStan\Command\CommandHelper;
use PHPStan\File\FileHelper;
use PHPStan\Php\PhpVersion;
use _PhpScoper006a73f0e455\Roave\BetterReflection\BetterReflection;
use _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber;
use function sys_get_temp_dir;
class ContainerFactory
{
    /**
     * @var string
     */
    private $currentWorkingDirectory;
    /**
     * @var \PHPStan\File\FileHelper
     */
    private $fileHelper;
    /**
     * @var string
     */
    private $rootDirectory;
    /**
     * @var string
     */
    private $configDirectory;
    public function __construct(string $currentWorkingDirectory)
    {
        $this->currentWorkingDirectory = $currentWorkingDirectory;
        $this->fileHelper = new \PHPStan\File\FileHelper($currentWorkingDirectory);
        $rootDir = __DIR__ . '/../..';
        $originalRootDir = $this->fileHelper->normalizePath($rootDir);
        if (\extension_loaded('phar')) {
            $pharPath = \Phar::running(\false);
            if ($pharPath !== '') {
                $rootDir = \dirname($pharPath);
            }
        }
        $this->rootDirectory = $this->fileHelper->normalizePath($rootDir);
        $this->configDirectory = $originalRootDir . '/conf';
    }
    /**
     * @param string $tempDirectory
     * @param string[] $additionalConfigFiles
     * @param string[] $analysedPaths
     * @param string[] $composerAutoloaderProjectPaths
     * @param string[] $analysedPathsFromConfig
     * @param string $usedLevel
     * @param string|null $generateBaselineFile
     * @param string|null $cliAutoloadFile
     * @param string|null $singleReflectionFile
     * @return \PHPStan\DependencyInjection\Container
     */
    public function create(string $tempDirectory, array $additionalConfigFiles, array $analysedPaths, array $composerAutoloaderProjectPaths = [], array $analysedPathsFromConfig = [], string $usedLevel = \PHPStan\Command\CommandHelper::DEFAULT_LEVEL, ?string $generateBaselineFile = null, ?string $cliAutoloadFile = null, ?string $singleReflectionFile = null) : \PHPStan\DependencyInjection\Container
    {
        $configurator = new \PHPStan\DependencyInjection\Configurator(new \PHPStan\DependencyInjection\LoaderFactory($this->fileHelper, $this->rootDirectory, $this->currentWorkingDirectory, $generateBaselineFile));
        $configurator->defaultExtensions = ['php' => \_PhpScoper006a73f0e455\Nette\DI\Extensions\PhpExtension::class, 'extensions' => \_PhpScoper006a73f0e455\Nette\DI\Extensions\ExtensionsExtension::class];
        $configurator->setDebugMode(\true);
        $configurator->setTempDirectory($tempDirectory);
        $configurator->addParameters(['rootDir' => $this->rootDirectory, 'currentWorkingDirectory' => $this->currentWorkingDirectory, 'cliArgumentsVariablesRegistered' => \ini_get('register_argc_argv') === '1', 'tmpDir' => $tempDirectory, 'additionalConfigFiles' => $additionalConfigFiles, 'analysedPaths' => $analysedPaths, 'composerAutoloaderProjectPaths' => $composerAutoloaderProjectPaths, 'analysedPathsFromConfig' => $analysedPathsFromConfig, 'generateBaselineFile' => $generateBaselineFile, 'usedLevel' => $usedLevel, 'cliAutoloadFile' => $cliAutoloadFile, 'fixerTmpDir' => \sys_get_temp_dir() . '/phpstan-fixer']);
        $configurator->addDynamicParameters(['singleReflectionFile' => $singleReflectionFile]);
        $configurator->addConfig($this->configDirectory . '/config.neon');
        foreach ($additionalConfigFiles as $additionalConfigFile) {
            $configurator->addConfig($additionalConfigFile);
        }
        $container = $configurator->createContainer();
        \_PhpScoper006a73f0e455\Roave\BetterReflection\BetterReflection::$phpVersion = $container->getByType(\PHPStan\Php\PhpVersion::class)->getVersionId();
        \_PhpScoper006a73f0e455\Roave\BetterReflection\BetterReflection::populate(
            $container->getService('betterReflectionSourceLocator'),
            // @phpstan-ignore-line
            $container->getService('betterReflectionClassReflector'),
            // @phpstan-ignore-line
            $container->getService('betterReflectionFunctionReflector'),
            // @phpstan-ignore-line
            $container->getService('betterReflectionConstantReflector'),
            // @phpstan-ignore-line
            $container->getService('phpParserDecorator'),
            // @phpstan-ignore-line
            $container->getByType(\_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber::class)
        );
        /** @var Broker $broker */
        $broker = $container->getByType(\PHPStan\Broker\Broker::class);
        \PHPStan\Broker\Broker::registerInstance($broker);
        $container->getService('typeSpecifier');
        return $container->getByType(\PHPStan\DependencyInjection\Container::class);
    }
    public function getCurrentWorkingDirectory() : string
    {
        return $this->currentWorkingDirectory;
    }
    public function getRootDirectory() : string
    {
        return $this->rootDirectory;
    }
    public function getConfigDirectory() : string
    {
        return $this->configDirectory;
    }
}