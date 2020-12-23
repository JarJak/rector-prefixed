<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\AttributeAwarePhpDoc\Ast\PhpDoc;

use _PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Attributes\Attribute\AttributeTrait;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\TypeAwareTagValueNodeInterface;
final class AttributeAwarePropertyTagValueNode extends \_PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode implements \_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface, \_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\TypeAwareTagValueNodeInterface
{
    use AttributeTrait;
}
