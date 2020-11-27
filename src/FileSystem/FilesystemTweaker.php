<?php

declare (strict_types=1);
namespace Rector\Core\FileSystem;

use _PhpScoper006a73f0e455\Nette\Utils\Strings;
use Rector\Core\Exception\FileSystem\DirectoryNotFoundException;
final class FilesystemTweaker
{
    /**
     * This will turn paths like "src/Symfony/Component/*\/Tests" to existing directory paths
     *
     * @param string[] $directories
     * @return string[]
     */
    public function resolveDirectoriesWithFnmatch(array $directories) : array
    {
        $absoluteDirectories = [];
        foreach ($directories as $directory) {
            // is fnmatch for directories
            if (\_PhpScoper006a73f0e455\Nette\Utils\Strings::contains($directory, '*')) {
                $absoluteDirectories = \array_merge($absoluteDirectories, \glob($directory, \GLOB_ONLYDIR));
            } else {
                // is classic directory
                $this->ensureDirectoryExists($directory);
                $absoluteDirectories[] = $directory;
            }
        }
        return $absoluteDirectories;
    }
    private function ensureDirectoryExists(string $directory) : void
    {
        if (\file_exists($directory)) {
            return;
        }
        throw new \Rector\Core\Exception\FileSystem\DirectoryNotFoundException(\sprintf('Directory "%s" was not found.', $directory));
    }
}
