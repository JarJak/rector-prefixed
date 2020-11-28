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
namespace _PhpScoperabd03f0baf05\phpDocumentor\Reflection\DocBlock\Tags;

use _PhpScoperabd03f0baf05\phpDocumentor\Reflection\DocBlock\Description;
use _PhpScoperabd03f0baf05\phpDocumentor\Reflection\DocBlock\DescriptionFactory;
use _PhpScoperabd03f0baf05\phpDocumentor\Reflection\Types\Context as TypeContext;
use _PhpScoperabd03f0baf05\Webmozart\Assert\Assert;
/**
 * Reflection class for a {@}since tag in a Docblock.
 */
final class Since extends \_PhpScoperabd03f0baf05\phpDocumentor\Reflection\DocBlock\Tags\BaseTag implements \_PhpScoperabd03f0baf05\phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod
{
    protected $name = 'since';
    /**
     * PCRE regular expression matching a version vector.
     * Assumes the "x" modifier.
     */
    const REGEX_VECTOR = '(?:
        # Normal release vectors.
        \\d\\S*
        |
        # VCS version vectors. Per PHPCS, they are expected to
        # follow the form of the VCS name, followed by ":", followed
        # by the version vector itself.
        # By convention, popular VCSes like CVS, SVN and GIT use "$"
        # around the actual version vector.
        [^\\s\\:]+\\:\\s*\\$[^\\$]+\\$
    )';
    /** @var string The version vector. */
    private $version = '';
    public function __construct($version = null, \_PhpScoperabd03f0baf05\phpDocumentor\Reflection\DocBlock\Description $description = null)
    {
        \_PhpScoperabd03f0baf05\Webmozart\Assert\Assert::nullOrStringNotEmpty($version);
        $this->version = $version;
        $this->description = $description;
    }
    /**
     * @return static
     */
    public static function create($body, \_PhpScoperabd03f0baf05\phpDocumentor\Reflection\DocBlock\DescriptionFactory $descriptionFactory = null, \_PhpScoperabd03f0baf05\phpDocumentor\Reflection\Types\Context $context = null)
    {
        \_PhpScoperabd03f0baf05\Webmozart\Assert\Assert::nullOrString($body);
        if (empty($body)) {
            return new static();
        }
        $matches = [];
        if (!\preg_match('/^(' . self::REGEX_VECTOR . ')\\s*(.+)?$/sux', $body, $matches)) {
            return null;
        }
        return new static($matches[1], $descriptionFactory->create(isset($matches[2]) ? $matches[2] : '', $context));
    }
    /**
     * Gets the version section of the tag.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
    /**
     * Returns a string representation for this tag.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->version . ($this->description ? ' ' . $this->description->render() : '');
    }
}
