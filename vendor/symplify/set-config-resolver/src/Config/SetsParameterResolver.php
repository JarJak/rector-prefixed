<?php

declare (strict_types=1);
namespace RectorPrefix20210118\Symplify\SetConfigResolver\Config;

use RectorPrefix20210118\Symfony\Component\Config\FileLocator;
use RectorPrefix20210118\Symfony\Component\DependencyInjection\ContainerBuilder;
use RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use RectorPrefix20210118\Symfony\Component\Yaml\Yaml;
use RectorPrefix20210118\Symplify\SetConfigResolver\SetResolver;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
final class SetsParameterResolver
{
    /**
     * @var string
     */
    private const SETS = 'sets';
    /**
     * @var SetResolver
     */
    private $setResolver;
    public function __construct(\RectorPrefix20210118\Symplify\SetConfigResolver\SetResolver $setResolver)
    {
        $this->setResolver = $setResolver;
    }
    /**
     * @param SmartFileInfo[] $fileInfos
     * @return SmartFileInfo[]
     */
    public function resolveFromFileInfos(array $fileInfos) : array
    {
        $setFileInfos = [];
        foreach ($fileInfos as $fileInfo) {
            $setsNames = $this->resolveSetsFromFileInfo($fileInfo);
            foreach ($setsNames as $setsName) {
                $setFileInfos[] = $this->setResolver->detectFromName($setsName);
            }
        }
        return $setFileInfos;
    }
    /**
     * @return string[]
     */
    private function resolveSetsFromFileInfo(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $configFileInfo) : array
    {
        if ($configFileInfo->hasSuffixes(['yml', 'yaml'])) {
            return $this->resolveSetsParameterFromYamlFileInfo($configFileInfo);
        }
        return $this->resolveSetsParameterFromPhpFileInfo($configFileInfo);
    }
    /**
     * @return string[]
     */
    private function resolveSetsParameterFromYamlFileInfo(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $configFileInfo) : array
    {
        $configContent = \RectorPrefix20210118\Symfony\Component\Yaml\Yaml::parse($configFileInfo->getContents());
        return (array) ($configContent['parameters'][self::SETS] ?? []);
    }
    /**
     * @return string[]
     */
    private function resolveSetsParameterFromPhpFileInfo(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $configFileInfo) : array
    {
        // php file loader
        $containerBuilder = new \RectorPrefix20210118\Symfony\Component\DependencyInjection\ContainerBuilder();
        $phpFileLoader = new \RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, new \RectorPrefix20210118\Symfony\Component\Config\FileLocator());
        $phpFileLoader->load($configFileInfo->getRealPath());
        if (!$containerBuilder->hasParameter(self::SETS)) {
            return [];
        }
        return (array) $containerBuilder->getParameter(self::SETS);
    }
}
