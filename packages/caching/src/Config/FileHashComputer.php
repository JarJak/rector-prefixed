<?php

declare (strict_types=1);
namespace Rector\Caching\Config;

use Rector\Core\Exception\ShouldNotHappenException;
use RectorPrefix20210118\Symfony\Component\Config\FileLocator;
use RectorPrefix20210118\Symfony\Component\Config\Loader\LoaderInterface;
use RectorPrefix20210118\Symfony\Component\Config\Loader\LoaderResolver;
use RectorPrefix20210118\Symfony\Component\DependencyInjection\ContainerBuilder;
use RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * Inspired by https://github.com/symplify/easy-coding-standard/blob/e598ab54686e416788f28fcfe007fd08e0f371d9/packages/changed-files-detector/src/FileHashComputer.php
 * @see \Rector\Caching\Tests\Config\FileHashComputerTest
 */
final class FileHashComputer
{
    public function compute(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : string
    {
        $this->ensureIsYamlOrPhp($fileInfo);
        $containerBuilder = new \RectorPrefix20210118\Symfony\Component\DependencyInjection\ContainerBuilder();
        $fileLoader = $this->createFileLoader($fileInfo, $containerBuilder);
        $fileLoader->load($fileInfo->getRealPath());
        $parameterBag = $containerBuilder->getParameterBag();
        return $this->arrayToHash($containerBuilder->getDefinitions()) . $this->arrayToHash($parameterBag->all());
    }
    private function ensureIsYamlOrPhp(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        if ($fileInfo->hasSuffixes(['yml', 'yaml', 'php'])) {
            return;
        }
        throw new \Rector\Core\Exception\ShouldNotHappenException(\sprintf(
            // getRealPath() cannot be used, as it breaks in phar
            'Provide only YAML/PHP file, ready for Symfony Dependency Injection. "%s" given',
            $fileInfo->getRelativeFilePath()
        ));
    }
    private function createFileLoader(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $fileInfo, \RectorPrefix20210118\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : \RectorPrefix20210118\Symfony\Component\Config\Loader\LoaderInterface
    {
        $fileLocator = new \RectorPrefix20210118\Symfony\Component\Config\FileLocator([$fileInfo->getPath()]);
        $fileLoaders = [new \RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\GlobFileLoader($containerBuilder, $fileLocator), new \RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\PhpFileLoader($containerBuilder, $fileLocator), new \RectorPrefix20210118\Symfony\Component\DependencyInjection\Loader\YamlFileLoader($containerBuilder, $fileLocator)];
        $loaderResolver = new \RectorPrefix20210118\Symfony\Component\Config\Loader\LoaderResolver($fileLoaders);
        $loader = $loaderResolver->resolve($fileInfo->getRealPath());
        if (!$loader) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        return $loader;
    }
    /**
     * @param mixed[] $array
     */
    private function arrayToHash(array $array) : string
    {
        return \md5(\serialize($array));
    }
}
