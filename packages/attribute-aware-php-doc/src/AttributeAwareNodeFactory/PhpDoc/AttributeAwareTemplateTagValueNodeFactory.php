<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\AttributeAwarePhpDoc\AttributeAwareNodeFactory\PhpDoc;

use _PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\Node;
use _PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use _PhpScoper0a2ac50786fa\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareTemplateTagValueNode;
use _PhpScoper0a2ac50786fa\Rector\AttributeAwarePhpDoc\Contract\AttributeNodeAwareFactory\AttributeNodeAwareFactoryInterface;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface;
final class AttributeAwareTemplateTagValueNodeFactory implements \_PhpScoper0a2ac50786fa\Rector\AttributeAwarePhpDoc\Contract\AttributeNodeAwareFactory\AttributeNodeAwareFactoryInterface
{
    public function getOriginalNodeClass() : string
    {
        return \_PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::class;
    }
    public function isMatch(\_PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\Node $node) : bool
    {
        return \is_a($node, \_PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode::class, \true);
    }
    /**
     * @param TemplateTagValueNode $node
     */
    public function create(\_PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\Node $node, string $docContent) : \_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface
    {
        return new \_PhpScoper0a2ac50786fa\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareTemplateTagValueNode($node->name, $node->bound, $node->description, $docContent);
    }
}
