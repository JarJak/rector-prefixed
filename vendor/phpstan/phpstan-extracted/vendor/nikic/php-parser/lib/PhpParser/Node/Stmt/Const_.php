<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PhpParser\Node\Stmt;

use _PhpScopere8e811afab72\PhpParser\Node;
class Const_ extends \_PhpScopere8e811afab72\PhpParser\Node\Stmt
{
    /** @var Node\Const_[] Constant declarations */
    public $consts;
    /**
     * Constructs a const list node.
     *
     * @param Node\Const_[] $consts     Constant declarations
     * @param array         $attributes Additional attributes
     */
    public function __construct(array $consts, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->consts = $consts;
    }
    public function getSubNodeNames() : array
    {
        return ['consts'];
    }
    public function getType() : string
    {
        return 'Stmt_Const';
    }
}