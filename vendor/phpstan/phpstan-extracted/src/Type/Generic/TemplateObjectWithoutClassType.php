<?php

declare (strict_types=1);
namespace PHPStan\Type\Generic;

use RectorPrefix20201227\PHPStan\TrinaryLogic;
use PHPStan\Type\CompoundType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Traits\UndecidedComparisonCompoundTypeTrait;
use PHPStan\Type\Type;
use PHPStan\Type\TypeUtils;
use PHPStan\Type\UnionType;
use PHPStan\Type\VerbosityLevel;
class TemplateObjectWithoutClassType extends \PHPStan\Type\ObjectWithoutClassType implements \PHPStan\Type\Generic\TemplateType
{
    use UndecidedComparisonCompoundTypeTrait;
    /** @var TemplateTypeScope */
    private $scope;
    /** @var string */
    private $name;
    /** @var TemplateTypeStrategy */
    private $strategy;
    /** @var TemplateTypeVariance */
    private $variance;
    /** @var ObjectWithoutClassType|null */
    private $bound = null;
    public function __construct(\PHPStan\Type\Generic\TemplateTypeScope $scope, \PHPStan\Type\Generic\TemplateTypeStrategy $templateTypeStrategy, \PHPStan\Type\Generic\TemplateTypeVariance $templateTypeVariance, string $name)
    {
        parent::__construct();
        $this->scope = $scope;
        $this->strategy = $templateTypeStrategy;
        $this->variance = $templateTypeVariance;
        $this->name = $name;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getScope() : \PHPStan\Type\Generic\TemplateTypeScope
    {
        return $this->scope;
    }
    public function getBound() : \PHPStan\Type\Type
    {
        if ($this->bound === null) {
            $this->bound = new \PHPStan\Type\ObjectWithoutClassType();
        }
        return $this->bound;
    }
    public function describe(\PHPStan\Type\VerbosityLevel $level) : string
    {
        $basicDescription = function () use($level) : string {
            return \sprintf('%s of %s', $this->name, parent::describe($level));
        };
        return $level->handle($basicDescription, $basicDescription, function () use($basicDescription) : string {
            return \sprintf('%s (%s, %s)', $basicDescription(), $this->scope->describe(), $this->isArgument() ? 'argument' : 'parameter');
        });
    }
    public function isArgument() : bool
    {
        return $this->strategy->isArgument();
    }
    public function toArgument() : \PHPStan\Type\Generic\TemplateType
    {
        return new self($this->scope, new \PHPStan\Type\Generic\TemplateTypeArgumentStrategy(), $this->variance, $this->name);
    }
    public function isValidVariance(\PHPStan\Type\Type $a, \PHPStan\Type\Type $b) : bool
    {
        return $this->variance->isValidVariance($a, $b);
    }
    public function subtract(\PHPStan\Type\Type $type) : \PHPStan\Type\Type
    {
        return $this;
    }
    public function getTypeWithoutSubtractedType() : \PHPStan\Type\Type
    {
        return $this;
    }
    public function changeSubtractedType(?\PHPStan\Type\Type $subtractedType) : \PHPStan\Type\Type
    {
        return $this;
    }
    public function equals(\PHPStan\Type\Type $type) : bool
    {
        return $type instanceof self && $type->scope->equals($this->scope) && $type->name === $this->name && parent::equals($type);
    }
    public function isAcceptedBy(\PHPStan\Type\Type $acceptingType, bool $strictTypes) : \RectorPrefix20201227\PHPStan\TrinaryLogic
    {
        return $this->isSubTypeOf($acceptingType);
    }
    public function accepts(\PHPStan\Type\Type $type, bool $strictTypes) : \RectorPrefix20201227\PHPStan\TrinaryLogic
    {
        return $this->strategy->accepts($this, $type, $strictTypes);
    }
    public function isSubTypeOf(\PHPStan\Type\Type $type) : \RectorPrefix20201227\PHPStan\TrinaryLogic
    {
        if ($type instanceof \PHPStan\Type\UnionType || $type instanceof \PHPStan\Type\IntersectionType) {
            return $type->isSuperTypeOf($this);
        }
        if (!$type instanceof \PHPStan\Type\Generic\TemplateType) {
            return $type->isSuperTypeOf($this->getBound());
        }
        if ($this->equals($type)) {
            return \RectorPrefix20201227\PHPStan\TrinaryLogic::createYes();
        }
        return $type->getBound()->isSuperTypeOf($this->getBound())->and(\RectorPrefix20201227\PHPStan\TrinaryLogic::createMaybe());
    }
    public function isSuperTypeOf(\PHPStan\Type\Type $type) : \RectorPrefix20201227\PHPStan\TrinaryLogic
    {
        if ($type instanceof \PHPStan\Type\CompoundType) {
            return $type->isSubTypeOf($this);
        }
        return $this->getBound()->isSuperTypeOf($type)->and(\RectorPrefix20201227\PHPStan\TrinaryLogic::createMaybe());
    }
    public function inferTemplateTypes(\PHPStan\Type\Type $receivedType) : \PHPStan\Type\Generic\TemplateTypeMap
    {
        if ($receivedType instanceof \PHPStan\Type\UnionType || $receivedType instanceof \PHPStan\Type\IntersectionType) {
            return $receivedType->inferTemplateTypesOn($this);
        }
        if ($receivedType instanceof \PHPStan\Type\Generic\TemplateType && $this->getBound()->isSuperTypeOf($receivedType->getBound())->yes()) {
            return new \PHPStan\Type\Generic\TemplateTypeMap([$this->name => $receivedType]);
        }
        if ($this->getBound()->isSuperTypeOf($receivedType)->yes()) {
            return new \PHPStan\Type\Generic\TemplateTypeMap([$this->name => \PHPStan\Type\TypeUtils::generalizeType($receivedType)]);
        }
        return \PHPStan\Type\Generic\TemplateTypeMap::createEmpty();
    }
    public function getReferencedTemplateTypes(\PHPStan\Type\Generic\TemplateTypeVariance $positionVariance) : array
    {
        return [new \PHPStan\Type\Generic\TemplateTypeReference($this, $positionVariance)];
    }
    public function getVariance() : \PHPStan\Type\Generic\TemplateTypeVariance
    {
        return $this->variance;
    }
    /**
     * @param mixed[] $properties
     * @return Type
     */
    public static function __set_state(array $properties) : \PHPStan\Type\Type
    {
        return new self($properties['scope'], $properties['strategy'], $properties['variance'], $properties['name']);
    }
}
