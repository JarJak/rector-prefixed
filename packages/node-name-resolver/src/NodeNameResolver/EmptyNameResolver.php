<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Empty_;
use _PhpScoper0a2ac50786fa\Rector\NodeNameResolver\Contract\NodeNameResolverInterface;
final class EmptyNameResolver implements \_PhpScoper0a2ac50786fa\Rector\NodeNameResolver\Contract\NodeNameResolverInterface
{
    public function getNode() : string
    {
        return \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Empty_::class;
    }
    /**
     * @param Empty_ $node
     */
    public function resolve(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?string
    {
        return 'empty';
    }
}
