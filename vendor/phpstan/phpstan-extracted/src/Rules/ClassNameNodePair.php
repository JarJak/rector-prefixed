<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Rules;

use _PhpScopere8e811afab72\PhpParser\Node;
class ClassNameNodePair
{
    /** @var string */
    private $className;
    /** @var Node */
    private $node;
    public function __construct(string $className, \_PhpScopere8e811afab72\PhpParser\Node $node)
    {
        $this->className = $className;
        $this->node = $node;
    }
    public function getClassName() : string
    {
        return $this->className;
    }
    public function getNode() : \_PhpScopere8e811afab72\PhpParser\Node
    {
        return $this->node;
    }
}