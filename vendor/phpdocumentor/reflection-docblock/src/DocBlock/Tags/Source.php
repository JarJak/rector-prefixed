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
namespace _PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\Tags;

use _PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\Description;
use _PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use _PhpScopera143bcca66cb\phpDocumentor\Reflection\Types\Context as TypeContext;
use _PhpScopera143bcca66cb\Webmozart\Assert\Assert;
/**
 * Reflection class for a {@}source tag in a Docblock.
 */
final class Source extends \_PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\Tags\BaseTag implements \_PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod
{
    /** @var string */
    protected $name = 'source';
    /** @var int The starting line, relative to the structural element's location. */
    private $startingLine = 1;
    /** @var int|null The number of lines, relative to the starting line. NULL means "to the end". */
    private $lineCount = null;
    public function __construct($startingLine, $lineCount = null, \_PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\Description $description = null)
    {
        \_PhpScopera143bcca66cb\Webmozart\Assert\Assert::integerish($startingLine);
        \_PhpScopera143bcca66cb\Webmozart\Assert\Assert::nullOrIntegerish($lineCount);
        $this->startingLine = (int) $startingLine;
        $this->lineCount = $lineCount !== null ? (int) $lineCount : null;
        $this->description = $description;
    }
    /**
     * {@inheritdoc}
     */
    public static function create($body, \_PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\DescriptionFactory $descriptionFactory = null, \_PhpScopera143bcca66cb\phpDocumentor\Reflection\Types\Context $context = null)
    {
        \_PhpScopera143bcca66cb\Webmozart\Assert\Assert::stringNotEmpty($body);
        \_PhpScopera143bcca66cb\Webmozart\Assert\Assert::notNull($descriptionFactory);
        $startingLine = 1;
        $lineCount = null;
        $description = null;
        // Starting line / Number of lines / Description
        if (\preg_match('/^([1-9]\\d*)\\s*(?:((?1))\\s+)?(.*)$/sux', $body, $matches)) {
            $startingLine = (int) $matches[1];
            if (isset($matches[2]) && $matches[2] !== '') {
                $lineCount = (int) $matches[2];
            }
            $description = $matches[3];
        }
        return new static($startingLine, $lineCount, $descriptionFactory->create($description, $context));
    }
    /**
     * Gets the starting line.
     *
     * @return int The starting line, relative to the structural element's
     *     location.
     */
    public function getStartingLine()
    {
        return $this->startingLine;
    }
    /**
     * Returns the number of lines.
     *
     * @return int|null The number of lines, relative to the starting line. NULL
     *     means "to the end".
     */
    public function getLineCount()
    {
        return $this->lineCount;
    }
    public function __toString()
    {
        return $this->startingLine . ($this->lineCount !== null ? ' ' . $this->lineCount : '') . ($this->description ? ' ' . $this->description->render() : '');
    }
}
