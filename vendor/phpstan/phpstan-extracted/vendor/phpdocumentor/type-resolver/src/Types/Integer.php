<?php

declare (strict_types=1);
/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link      http://phpdoc.org
 */
namespace _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Types;

use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Type;
/**
 * Value object representing Integer type
 *
 * @psalm-immutable
 */
final class Integer implements \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Type
{
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString() : string
    {
        return 'int';
    }
}
