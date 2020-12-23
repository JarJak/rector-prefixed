<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Php72\Contract;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Identifier;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Name;
use _PhpScoper0a2ac50786fa\PhpParser\Node\NullableType;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Param;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Expression;
use _PhpScoper0a2ac50786fa\PhpParser\Node\UnionType;
interface ConvertToAnonymousFunctionRectorInterface
{
    public function shouldSkip(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : bool;
    /**
     * @return Param[]
     */
    public function getParameters(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : array;
    /**
     * @return Identifier|Name|NullableType|UnionType|null
     */
    public function getReturnType(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node;
    /**
     * @return Expression[]|Stmt[]
     */
    public function getBody(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : array;
}
