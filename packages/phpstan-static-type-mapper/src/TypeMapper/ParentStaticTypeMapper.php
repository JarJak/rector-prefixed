<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\PHPStanStaticTypeMapper\TypeMapper;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Name;
use _PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\Type\TypeNode;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
use _PhpScoper0a2ac50786fa\PHPStan\Type\VerbosityLevel;
use _PhpScoper0a2ac50786fa\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareIdentifierTypeNode;
use _PhpScoper0a2ac50786fa\Rector\PHPStan\Type\ParentStaticType;
use _PhpScoper0a2ac50786fa\Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
final class ParentStaticTypeMapper implements \_PhpScoper0a2ac50786fa\Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface
{
    public function getNodeClass() : string
    {
        return \_PhpScoper0a2ac50786fa\Rector\PHPStan\Type\ParentStaticType::class;
    }
    /**
     * @param ParentStaticType $type
     */
    public function mapToPHPStanPhpDocTypeNode(\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $type) : \_PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\Type\TypeNode
    {
        return new \_PhpScoper0a2ac50786fa\Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareIdentifierTypeNode('parent');
    }
    /**
     * @param ParentStaticType $type
     */
    public function mapToPhpParserNode(\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $type, ?string $kind = null) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        return new \_PhpScoper0a2ac50786fa\PhpParser\Node\Name('parent');
    }
    public function mapToDocString(\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $type, ?\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $parentType = null) : string
    {
        return $type->describe(\_PhpScoper0a2ac50786fa\PHPStan\Type\VerbosityLevel::typeOnly());
    }
}
