<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Type;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use _PhpScoper26e51eeacccf\Roave\BetterReflection\Identifier\Identifier;
use _PhpScoper26e51eeacccf\Roave\BetterReflection\Identifier\IdentifierType;
use _PhpScoper26e51eeacccf\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoper26e51eeacccf\Roave\BetterReflection\Reflector\Reflector;
use _PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Ast\Locator;
use _PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Exception\InvalidDirectory;
use _PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Exception\InvalidFileInfo;
use function array_map;
use function array_values;
use function is_dir;
use function is_string;
/**
 * This source locator recursively loads all php files in an entire directory or multiple directories.
 */
class DirectoriesSourceLocator implements \_PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Type\SourceLocator
{
    /** @var AggregateSourceLocator */
    private $aggregateSourceLocator;
    /**
     * @param string[] $directories directories to scan
     *
     * @throws InvalidDirectory
     * @throws InvalidFileInfo
     */
    public function __construct(array $directories, \_PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Ast\Locator $astLocator)
    {
        $this->aggregateSourceLocator = new \_PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator(\array_values(\array_map(static function ($directory) use($astLocator) : FileIteratorSourceLocator {
            if (!\is_string($directory)) {
                throw \_PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Exception\InvalidDirectory::fromNonStringValue($directory);
            }
            if (!\is_dir($directory)) {
                throw \_PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Exception\InvalidDirectory::fromNonDirectory($directory);
            }
            return new \_PhpScoper26e51eeacccf\Roave\BetterReflection\SourceLocator\Type\FileIteratorSourceLocator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)), $astLocator);
        }, $directories)));
    }
    public function locateIdentifier(\_PhpScoper26e51eeacccf\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoper26e51eeacccf\Roave\BetterReflection\Identifier\Identifier $identifier) : ?\_PhpScoper26e51eeacccf\Roave\BetterReflection\Reflection\Reflection
    {
        return $this->aggregateSourceLocator->locateIdentifier($reflector, $identifier);
    }
    /**
     * {@inheritDoc}
     */
    public function locateIdentifiersByType(\_PhpScoper26e51eeacccf\Roave\BetterReflection\Reflector\Reflector $reflector, \_PhpScoper26e51eeacccf\Roave\BetterReflection\Identifier\IdentifierType $identifierType) : array
    {
        return $this->aggregateSourceLocator->locateIdentifiersByType($reflector, $identifierType);
    }
}
