<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Reflection\Annotations;

use _PhpScoper0a2ac50786fa\PHPStan\Reflection\ClassMemberReflection;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\ClassReflection;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\FunctionVariant;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\MethodReflection;
use _PhpScoper0a2ac50786fa\PHPStan\TrinaryLogic;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Generic\TemplateTypeMap;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
class AnnotationMethodReflection implements \_PhpScoper0a2ac50786fa\PHPStan\Reflection\MethodReflection
{
    /** @var string */
    private $name;
    /** @var \PHPStan\Reflection\ClassReflection */
    private $declaringClass;
    /** @var Type */
    private $returnType;
    /** @var bool */
    private $isStatic;
    /** @var \PHPStan\Reflection\Annotations\AnnotationsMethodParameterReflection[] */
    private $parameters;
    /** @var bool */
    private $isVariadic;
    /** @var FunctionVariant[]|null */
    private $variants = null;
    /**
     * @param string $name
     * @param ClassReflection $declaringClass
     * @param Type $returnType
     * @param \PHPStan\Reflection\Annotations\AnnotationsMethodParameterReflection[] $parameters
     * @param bool $isStatic
     * @param bool $isVariadic
     */
    public function __construct(string $name, \_PhpScoper0a2ac50786fa\PHPStan\Reflection\ClassReflection $declaringClass, \_PhpScoper0a2ac50786fa\PHPStan\Type\Type $returnType, array $parameters, bool $isStatic, bool $isVariadic)
    {
        $this->name = $name;
        $this->declaringClass = $declaringClass;
        $this->returnType = $returnType;
        $this->parameters = $parameters;
        $this->isStatic = $isStatic;
        $this->isVariadic = $isVariadic;
    }
    public function getDeclaringClass() : \_PhpScoper0a2ac50786fa\PHPStan\Reflection\ClassReflection
    {
        return $this->declaringClass;
    }
    public function getPrototype() : \_PhpScoper0a2ac50786fa\PHPStan\Reflection\ClassMemberReflection
    {
        return $this;
    }
    public function isStatic() : bool
    {
        return $this->isStatic;
    }
    public function isPrivate() : bool
    {
        return \false;
    }
    public function isPublic() : bool
    {
        return \true;
    }
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * @return \PHPStan\Reflection\ParametersAcceptor[]
     */
    public function getVariants() : array
    {
        if ($this->variants === null) {
            $this->variants = [new \_PhpScoper0a2ac50786fa\PHPStan\Reflection\FunctionVariant(\_PhpScoper0a2ac50786fa\PHPStan\Type\Generic\TemplateTypeMap::createEmpty(), null, $this->parameters, $this->isVariadic, $this->returnType)];
        }
        return $this->variants;
    }
    public function isDeprecated() : \_PhpScoper0a2ac50786fa\PHPStan\TrinaryLogic
    {
        return \_PhpScoper0a2ac50786fa\PHPStan\TrinaryLogic::createNo();
    }
    public function getDeprecatedDescription() : ?string
    {
        return null;
    }
    public function isFinal() : \_PhpScoper0a2ac50786fa\PHPStan\TrinaryLogic
    {
        return \_PhpScoper0a2ac50786fa\PHPStan\TrinaryLogic::createNo();
    }
    public function isInternal() : \_PhpScoper0a2ac50786fa\PHPStan\TrinaryLogic
    {
        return \_PhpScoper0a2ac50786fa\PHPStan\TrinaryLogic::createNo();
    }
    public function getThrowType() : ?\_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        return null;
    }
    public function hasSideEffects() : \_PhpScoper0a2ac50786fa\PHPStan\TrinaryLogic
    {
        return \_PhpScoper0a2ac50786fa\PHPStan\TrinaryLogic::createMaybe();
    }
    public function getDocComment() : ?string
    {
        return null;
    }
}
