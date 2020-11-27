<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\Composer\Factory\Exception;

use UnexpectedValueException;
use function sprintf;
final class MissingInstalledJson extends \UnexpectedValueException implements \_PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Type\Composer\Factory\Exception\Exception
{
    public static function inProjectPath(string $path) : self
    {
        return new self(\sprintf('Could not locate a "vendor/composer/installed.json" file in "%s"', $path));
    }
}