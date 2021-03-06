<?php

declare (strict_types=1);
namespace RectorPrefix20210118\Symplify\SmartFileSystem\Finder;

use RectorPrefix20210118\Symfony\Component\Finder\Finder;
use RectorPrefix20210118\Symplify\SmartFileSystem\FileSystemFilter;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Symplify\SmartFileSystem\Tests\Finder\SmartFinder\SmartFinderTest
 */
final class SmartFinder
{
    /**
     * @var FinderSanitizer
     */
    private $finderSanitizer;
    /**
     * @var FileSystemFilter
     */
    private $fileSystemFilter;
    public function __construct(\RectorPrefix20210118\Symplify\SmartFileSystem\Finder\FinderSanitizer $finderSanitizer, \RectorPrefix20210118\Symplify\SmartFileSystem\FileSystemFilter $fileSystemFilter)
    {
        $this->finderSanitizer = $finderSanitizer;
        $this->fileSystemFilter = $fileSystemFilter;
    }
    /**
     * @return SmartFileInfo[]
     */
    public function findPaths(array $directoriesOrFiles, string $path) : array
    {
        $directories = $this->fileSystemFilter->filterDirectories($directoriesOrFiles);
        $fileInfos = [];
        if ($directories !== []) {
            $finder = new \RectorPrefix20210118\Symfony\Component\Finder\Finder();
            $finder->name('*')->in($directories)->path($path)->files()->sortByName();
            $fileInfos = $this->finderSanitizer->sanitize($finder);
        }
        return $fileInfos;
    }
    /**
     * @param string[] $excludedDirectories
     * @return SmartFileInfo[]
     */
    public function find(array $directoriesOrFiles, string $name, array $excludedDirectories = []) : array
    {
        $directories = $this->fileSystemFilter->filterDirectories($directoriesOrFiles);
        $fileInfos = [];
        if ($directories !== []) {
            $finder = new \RectorPrefix20210118\Symfony\Component\Finder\Finder();
            $finder->name($name)->in($directories)->files()->sortByName();
            if ($excludedDirectories !== []) {
                $finder->exclude($excludedDirectories);
            }
            $fileInfos = $this->finderSanitizer->sanitize($finder);
        }
        $files = $this->fileSystemFilter->filterFiles($directoriesOrFiles);
        foreach ($files as $file) {
            $fileInfos[] = new \RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo($file);
        }
        return $fileInfos;
    }
}
