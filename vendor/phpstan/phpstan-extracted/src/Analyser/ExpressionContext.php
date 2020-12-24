<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Analyser;

use _PhpScopere8e811afab72\PHPStan\Type\Type;
class ExpressionContext
{
    /** @var bool */
    private $isDeep;
    /** @var string|null */
    private $inAssignRightSideVariableName;
    /** @var Type|null */
    private $inAssignRightSideType;
    private function __construct(bool $isDeep, ?string $inAssignRightSideVariableName, ?\_PhpScopere8e811afab72\PHPStan\Type\Type $inAssignRightSideType)
    {
        $this->isDeep = $isDeep;
        $this->inAssignRightSideVariableName = $inAssignRightSideVariableName;
        $this->inAssignRightSideType = $inAssignRightSideType;
    }
    public static function createTopLevel() : self
    {
        return new self(\false, null, null);
    }
    public static function createDeep() : self
    {
        return new self(\true, null, null);
    }
    public function enterDeep() : self
    {
        if ($this->isDeep) {
            return $this;
        }
        return new self(\true, $this->inAssignRightSideVariableName, $this->inAssignRightSideType);
    }
    public function isDeep() : bool
    {
        return $this->isDeep;
    }
    public function enterRightSideAssign(string $variableName, \_PhpScopere8e811afab72\PHPStan\Type\Type $type) : self
    {
        return new self($this->isDeep, $variableName, $type);
    }
    public function getInAssignRightSideVariableName() : ?string
    {
        return $this->inAssignRightSideVariableName;
    }
    public function getInAssignRightSideType() : ?\_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        return $this->inAssignRightSideType;
    }
}