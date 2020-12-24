<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\DependencyInjection;

use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI\Extensions\PhpExtension;
use Phar;
use _PhpScopere8e811afab72\PHPStan\Broker\Broker;
use _PhpScopere8e811afab72\PHPStan\Command\CommandHelper;
use _PhpScopere8e811afab72\PHPStan\File\FileHelper;
use _PhpScopere8e811afab72\PHPStan\Php\PhpVersion;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\BetterReflection;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber;
use function sys_get_temp_dir;
class ContainerFactory
{
    /** @var string */
    private $currentWorkingDirectory;
    /** @var FileHelper */
    private $fileHelper;
    /** @var string */
    private $rootDirectory;
    /** @var string */
    private $configDirectory;
    public function __construct(string $currentWorkingDirectory)
    {
        $this->currentWorkingDirectory = $currentWorkingDirectory;
        $this->fileHelper = new \_PhpScopere8e811afab72\PHPStan\File\FileHelper($currentWorkingDirectory);
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
    public function create(string $tempDirectory, array $additionalConfigFiles, array $analysedPaths, array $composerAutoloaderProjectPaths = [], array $analysedPathsFromConfig = [], string $usedLevel = \_PhpScopere8e811afab72\PHPStan\Command\CommandHelper::DEFAULT_LEVEL, ?string $generateBaselineFile = null, ?string $cliAutoloadFile = null, ?string $singleReflectionFile = null) : \_PhpScopere8e811afab72\PHPStan\DependencyInjection\Container
    {
        $configurator = new \_PhpScopere8e811afab72\PHPStan\DependencyInjection\Configurator(new \_PhpScopere8e811afab72\PHPStan\DependencyInjection\LoaderFactory($this->fileHelper, $this->rootDirectory, $this->currentWorkingDirectory, $generateBaselineFile));
        $configurator->defaultExtensions = ['php' => \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI\Extensions\PhpExtension::class, 'extensions' => \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\DI\Extensions\ExtensionsExtension::class];
        $configurator->setDebugMode(\true);
        $configurator->setTempDirectory($tempDirectory);
        $configurator->addParameters(['rootDir' => $this->rootDirectory, 'currentWorkingDirectory' => $this->currentWorkingDirectory, 'cliArgumentsVariablesRegistered' => \ini_get('register_argc_argv') === '1', 'tmpDir' => $tempDirectory, 'additionalConfigFiles' => $additionalConfigFiles, 'analysedPaths' => $analysedPaths, 'composerAutoloaderProjectPaths' => $composerAutoloaderProjectPaths, 'analysedPathsFromConfig' => $analysedPathsFromConfig, 'generateBaselineFile' => $generateBaselineFile, 'usedLevel' => $usedLevel, 'cliAutoloadFile' => $cliAutoloadFile, 'fixerTmpDir' => \sys_get_temp_dir() . '/phpstan-fixer']);
        $configurator->addDynamicParameters(['singleReflectionFile' => $singleReflectionFile]);
        $configurator->addConfig($this->configDirectory . '/config.neon');
        foreach ($additionalConfigFiles as $additionalConfigFile) {
            $configurator->addConfig($additionalConfigFile);
        }
        $container = $configurator->createContainer();
        \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\BetterReflection::$phpVersion = $container->getByType(\_PhpScopere8e811afab72\PHPStan\Php\PhpVersion::class)->getVersionId();
        \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\BetterReflection::populate(
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
            $container->getByType(\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber::class)
        );
        /** @var Broker $broker */
        $broker = $container->getByType(\_PhpScopere8e811afab72\PHPStan\Broker\Broker::class);
        \_PhpScopere8e811afab72\PHPStan\Broker\Broker::registerInstance($broker);
        $container->getService('typeSpecifier');
        return $container->getByType(\_PhpScopere8e811afab72\PHPStan\DependencyInjection\Container::class);
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