<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Roave\BetterReflection\SourceLocator\Exception;

use LogicException;
use function sprintf;
class TwoAnonymousClassesOnSameLine extends \LogicException
{
    public static function create(string $fileName, int $lineNumber) : self
    {
        return new self(\sprintf('Two anonymous classes on line %d in %s', $lineNumber, $fileName));
    }
}