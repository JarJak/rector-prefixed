<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Closure;
use _PhpScoper0a2ac50786fa\PhpParser\Node\FunctionLike;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Function_;
use _PhpScoper0a2ac50786fa\PHPStan\Type\MixedType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Contract\TypeInferer\ReturnTypeInfererInterface;
use _PhpScoper0a2ac50786fa\Rector\TypeDeclaration\TypeInferer\AbstractTypeInferer;
final class ReturnTagReturnTypeInferer extends \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\TypeInferer\AbstractTypeInferer implements \_PhpScoper0a2ac50786fa\Rector\TypeDeclaration\Contract\TypeInferer\ReturnTypeInfererInterface
{
    /**
     * @param ClassMethod|Closure|Function_ $functionLike
     */
    public function inferFunctionLike(\_PhpScoper0a2ac50786fa\PhpParser\Node\FunctionLike $functionLike) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $functionLike->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType();
        }
        return $phpDocInfo->getReturnType();
    }
    public function getPriority() : int
    {
        return 400;
    }
}
