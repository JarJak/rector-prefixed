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
namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Types;

use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Type;
/**
 * Represents a list of values. This is an abstract class for Array_ and Collection.
 *
 * @psalm-immutable
 */
abstract class AbstractList implements \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Type
{
    /** @var Type */
    protected $valueType;
    /** @var Type|null */
    protected $keyType;
    /** @var Type */
    protected $defaultKeyType;
    /**
     * Initializes this representation of an array with the given Type.
     */
    public function __construct(?\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Type $valueType = null, ?\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Type $keyType = null)
    {
        if ($valueType === null) {
            $valueType = new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Types\Mixed_();
        }
        $this->valueType = $valueType;
        $this->defaultKeyType = new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Types\Compound([new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Types\String_(), new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Types\Integer()]);
        $this->keyType = $keyType;
    }
    /**
     * Returns the type for the keys of this array.
     */
    public function getKeyType() : \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Type
    {
        return $this->keyType ?? $this->defaultKeyType;
    }
    /**
     * Returns the value for the keys of this array.
     */
    public function getValueType() : \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Type
    {
        return $this->valueType;
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString() : string
    {
        if ($this->keyType) {
            return 'array<' . $this->keyType . ',' . $this->valueType . '>';
        }
        if ($this->valueType instanceof \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Types\Mixed_) {
            return 'array';
        }
        if ($this->valueType instanceof \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\Types\Compound) {
            return '(' . $this->valueType . ')[]';
        }
        return $this->valueType . '[]';
    }
}