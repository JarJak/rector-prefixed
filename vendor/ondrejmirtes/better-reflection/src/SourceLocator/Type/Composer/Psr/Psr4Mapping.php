<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\Composer\Psr;

use _PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\Identifier;
use function array_filter;
use function array_keys;
use function array_map;
use function array_merge;
use function array_unique;
use function array_values;
use function ltrim;
use function rtrim;
use function str_replace;
use function strlen;
use function strpos;
use function substr;
use const ARRAY_FILTER_USE_KEY;
final class Psr4Mapping implements \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\Composer\Psr\PsrAutoloaderMapping
{
    /** @var array<string, array<int, string>> */
    private $mappings = [];
    private function __construct()
    {
    }
    /** @param array<string, array<int, string>> $mappings */
    public static function fromArrayMappings(array $mappings) : self
    {
        $instance = new self();
        $instance->mappings = \array_map(static function (array $directories) : array {
            return \array_map(static function (string $directory) : string {
                return \rtrim($directory, '/');
            }, $directories);
        }, $mappings);
        return $instance;
    }
    /** {@inheritDoc} */
    public function resolvePossibleFilePaths(\_PhpScoper006a73f0e455\Roave\BetterReflection\Identifier\Identifier $identifier) : array
    {
        if (!$identifier->isClass()) {
            return [];
        }
        $className = $identifier->getName();
        $matchingPrefixes = $this->matchingPrefixes($className);
        return \array_values(\array_filter(\array_merge([], ...\array_map(static function (array $paths, string $prefix) use($className) : array {
            $subPath = \ltrim(\str_replace('\\', '/', \substr($className, \strlen($prefix))), '/');
            if ($subPath === '') {
                return [];
            }
            return \array_map(static function (string $path) use($subPath) : string {
                return \rtrim($path, '/') . '/' . $subPath . '.php';
            }, $paths);
        }, $matchingPrefixes, \array_keys($matchingPrefixes)))));
    }
    /** @return array<string, array<int, string>> */
    private function matchingPrefixes(string $className) : array
    {
        return \array_filter($this->mappings, static function (string $prefix) use($className) : bool {
            if ($prefix === '') {
                return \false;
            }
            return \strpos($className, $prefix) === 0;
        }, \ARRAY_FILTER_USE_KEY);
    }
    /** {@inheritDoc} */
    public function directories() : array
    {
        return \array_values(\array_unique(\array_merge([], ...\array_values($this->mappings))));
    }
}
