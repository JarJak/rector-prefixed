<?php

declare (strict_types=1);
namespace PHPStan\Type\Generic;

class TemplateTypeReference
{
    /**
     * @var \PHPStan\Type\Generic\TemplateType
     */
    private $type;
    /**
     * @var \PHPStan\Type\Generic\TemplateTypeVariance
     */
    private $positionVariance;
    public function __construct(\PHPStan\Type\Generic\TemplateType $type, \PHPStan\Type\Generic\TemplateTypeVariance $positionVariance)
    {
        $this->type = $type;
        $this->positionVariance = $positionVariance;
    }
    public function getType() : \PHPStan\Type\Generic\TemplateType
    {
        return $this->type;
    }
    public function getPositionVariance() : \PHPStan\Type\Generic\TemplateTypeVariance
    {
        return $this->positionVariance;
    }
}
