<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\NodeNestingScope\ValueObject;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Match_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\FunctionLike;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Case_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Catch_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Do_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Else_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ElseIf_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\If_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Switch_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\While_;
final class ControlStructure
{
    /**
     * @var class-string[]
     */
    public const BREAKING_SCOPE_NODE_TYPES = [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\For_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\If_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\While_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Do_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Else_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ElseIf_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Catch_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Case_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\FunctionLike::class];
    /**
     * These situations happens only if condition is met
     * @var class-string[]
     */
    public const CONDITIONAL_NODE_SCOPE_TYPES = [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\If_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\While_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Do_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Else_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ElseIf_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Catch_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Case_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Match_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Switch_::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Foreach_::class];
}
