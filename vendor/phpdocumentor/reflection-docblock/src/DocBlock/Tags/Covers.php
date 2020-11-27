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
use _PhpScopera143bcca66cb\phpDocumentor\Reflection\Fqsen;
use _PhpScopera143bcca66cb\phpDocumentor\Reflection\FqsenResolver;
use _PhpScopera143bcca66cb\phpDocumentor\Reflection\Types\Context as TypeContext;
use _PhpScopera143bcca66cb\Webmozart\Assert\Assert;
/**
 * Reflection class for a @covers tag in a Docblock.
 */
final class Covers extends \_PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\Tags\BaseTag implements \_PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod
{
    protected $name = 'covers';
    /** @var Fqsen */
    private $refers = null;
    /**
     * Initializes this tag.
     *
     * @param Fqsen $refers
     * @param Description $description
     */
    public function __construct(\_PhpScopera143bcca66cb\phpDocumentor\Reflection\Fqsen $refers, \_PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\Description $description = null)
    {
        $this->refers = $refers;
        $this->description = $description;
    }
    /**
     * {@inheritdoc}
     */
    public static function create($body, \_PhpScopera143bcca66cb\phpDocumentor\Reflection\DocBlock\DescriptionFactory $descriptionFactory = null, \_PhpScopera143bcca66cb\phpDocumentor\Reflection\FqsenResolver $resolver = null, \_PhpScopera143bcca66cb\phpDocumentor\Reflection\Types\Context $context = null)
    {
        \_PhpScopera143bcca66cb\Webmozart\Assert\Assert::string($body);
        \_PhpScopera143bcca66cb\Webmozart\Assert\Assert::notEmpty($body);
        $parts = \preg_split('/\\s+/Su', $body, 2);
        return new static($resolver->resolve($parts[0], $context), $descriptionFactory->create(isset($parts[1]) ? $parts[1] : '', $context));
    }
    /**
     * Returns the structural element this tag refers to.
     *
     * @return Fqsen
     */
    public function getReference()
    {
        return $this->refers;
    }
    /**
     * Returns a string representation of this tag.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->refers . ($this->description ? ' ' . $this->description->render() : '');
    }
}
