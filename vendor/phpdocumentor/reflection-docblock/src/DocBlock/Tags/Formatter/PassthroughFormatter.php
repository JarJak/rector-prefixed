<?php

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright 2010-2015 Mike van Riel<mike@phpdoc.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
namespace _PhpScoper26e51eeacccf\phpDocumentor\Reflection\DocBlock\Tags\Formatter;

use _PhpScoper26e51eeacccf\phpDocumentor\Reflection\DocBlock\Tag;
use _PhpScoper26e51eeacccf\phpDocumentor\Reflection\DocBlock\Tags\Formatter;
class PassthroughFormatter implements \_PhpScoper26e51eeacccf\phpDocumentor\Reflection\DocBlock\Tags\Formatter
{
    /**
     * Formats the given tag to return a simple plain text version.
     *
     * @param Tag $tag
     *
     * @return string
     */
    public function format(\_PhpScoper26e51eeacccf\phpDocumentor\Reflection\DocBlock\Tag $tag)
    {
        return \trim('@' . $tag->getName() . ' ' . (string) $tag);
    }
}
