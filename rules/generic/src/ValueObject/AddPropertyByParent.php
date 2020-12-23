<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Generic\ValueObject;

final class AddPropertyByParent
{
    /**
     * @var string
     */
    private $parentClass;
    /**
     * @var string
     */
    private $dependencyType;
    public function __construct(string $parentClass, string $dependencyType)
    {
        $this->parentClass = $parentClass;
        $this->dependencyType = $dependencyType;
    }
    public function getParentClass() : string
    {
        return $this->parentClass;
    }
    public function getDependencyType() : string
    {
        return $this->dependencyType;
    }
}
