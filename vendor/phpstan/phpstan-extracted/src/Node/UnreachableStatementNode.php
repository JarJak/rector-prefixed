<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Node;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt;
class UnreachableStatementNode extends \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt implements \_PhpScoper0a2ac50786fa\PHPStan\Node\VirtualNode
{
    /** @var Stmt */
    private $originalStatement;
    public function __construct(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt $originalStatement)
    {
        parent::__construct($originalStatement->getAttributes());
        $this->originalStatement = $originalStatement;
    }
    public function getOriginalStatement() : \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt
    {
        return $this->originalStatement;
    }
    public function getType() : string
    {
        return 'PHPStan_Stmt_UnreachableStatementNode';
    }
    /**
     * @return string[]
     */
    public function getSubNodeNames() : array
    {
        return [];
    }
}
