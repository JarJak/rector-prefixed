<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Exception;

use InvalidArgumentException;
class NoObjectProvided extends \InvalidArgumentException
{
    public static function create() : self
    {
        return new self('No object provided');
    }
}