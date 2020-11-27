<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\Exception;

use InvalidArgumentException;
use function gettype;
use function sprintf;
class NotAnObject extends \InvalidArgumentException
{
    /**
     * @param bool|int|float|string|array|resource|null $nonObject
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
     */
    public static function fromNonObject($nonObject) : self
    {
        return new self(\sprintf('Provided "%s" is not an object', \gettype($nonObject)));
    }
}
