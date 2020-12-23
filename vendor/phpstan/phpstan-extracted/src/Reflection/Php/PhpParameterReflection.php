<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Reflection\Php;

use _PhpScoper0a2ac50786fa\PHPStan\Reflection\ParameterReflectionWithPhpDocs;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\PassedByReference;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ConstantTypeHelper;
use _PhpScoper0a2ac50786fa\PHPStan\Type\MixedType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
use _PhpScoper0a2ac50786fa\PHPStan\Type\TypehintHelper;
class PhpParameterReflection implements \_PhpScoper0a2ac50786fa\PHPStan\Reflection\ParameterReflectionWithPhpDocs
{
    /** @var \ReflectionParameter */
    private $reflection;
    /** @var \PHPStan\Type\Type|null */
    private $phpDocType;
    /** @var \PHPStan\Type\Type|null */
    private $type = null;
    /** @var \PHPStan\Type\Type|null */
    private $nativeType = null;
    /** @var string|null */
    private $declaringClassName;
    public function __construct(\ReflectionParameter $reflection, ?\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $phpDocType, ?string $declaringClassName)
    {
        $this->reflection = $reflection;
        $this->phpDocType = $phpDocType;
        $this->declaringClassName = $declaringClassName;
    }
    public function isOptional() : bool
    {
        return $this->reflection->isOptional();
    }
    public function getName() : string
    {
        return $this->reflection->getName();
    }
    public function getType() : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        if ($this->type === null) {
            $phpDocType = $this->phpDocType;
            if ($phpDocType !== null) {
                try {
                    if ($this->reflection->isDefaultValueAvailable() && $this->reflection->getDefaultValue() === null) {
                        $phpDocType = \_PhpScoper0a2ac50786fa\PHPStan\Type\TypeCombinator::addNull($phpDocType);
                    }
                } catch (\Throwable $e) {
                    // pass
                }
            }
            $this->type = \_PhpScoper0a2ac50786fa\PHPStan\Type\TypehintHelper::decideTypeFromReflection($this->reflection->getType(), $phpDocType, $this->declaringClassName, $this->isVariadic());
        }
        return $this->type;
    }
    public function passedByReference() : \_PhpScoper0a2ac50786fa\PHPStan\Reflection\PassedByReference
    {
        return $this->reflection->isPassedByReference() ? \_PhpScoper0a2ac50786fa\PHPStan\Reflection\PassedByReference::createCreatesNewVariable() : \_PhpScoper0a2ac50786fa\PHPStan\Reflection\PassedByReference::createNo();
    }
    public function isVariadic() : bool
    {
        return $this->reflection->isVariadic();
    }
    public function getPhpDocType() : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        if ($this->phpDocType !== null) {
            return $this->phpDocType;
        }
        return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
    }
    public function getNativeType() : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        if ($this->nativeType === null) {
            $this->nativeType = \_PhpScoper0a2ac50786fa\PHPStan\Type\TypehintHelper::decideTypeFromReflection($this->reflection->getType(), null, $this->declaringClassName, $this->isVariadic());
        }
        return $this->nativeType;
    }
    public function getDefaultValue() : ?\_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        try {
            if ($this->reflection->isDefaultValueAvailable()) {
                $defaultValue = $this->reflection->getDefaultValue();
                return \_PhpScoper0a2ac50786fa\PHPStan\Type\ConstantTypeHelper::getTypeFromValue($defaultValue);
            }
        } catch (\Throwable $e) {
            return null;
        }
        return null;
    }
}
