<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\PHPStan;

use _PhpScoper0a2ac50786fa\PHPStan\ShouldNotHappenException;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ConstantType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Generic\GenericObjectType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\MixedType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
use _PhpScoper0a2ac50786fa\PHPStan\Type\TypeWithClassName;
use _PhpScoper0a2ac50786fa\PHPStan\Type\UnionType;
use _PhpScoper0a2ac50786fa\Rector\PHPStan\Type\AliasedObjectType;
use _PhpScoper0a2ac50786fa\Rector\PHPStan\Type\ShortenedObjectType;
use _PhpScoper0a2ac50786fa\Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
final class TypeHasher
{
    /**
     * @var PHPStanStaticTypeMapper
     */
    private $phpStanStaticTypeMapper;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper $phpStanStaticTypeMapper)
    {
        $this->phpStanStaticTypeMapper = $phpStanStaticTypeMapper;
    }
    public function createTypeHash(\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $type) : string
    {
        if ($type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType) {
            return \serialize($type);
        }
        if ($type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType) {
            return $this->createTypeHash($type->getItemType()) . '[]';
        }
        if ($type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\Generic\GenericObjectType) {
            return $this->phpStanStaticTypeMapper->mapToDocString($type);
        }
        if ($type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\TypeWithClassName) {
            return $this->resolveUniqueTypeWithClassNameHash($type);
        }
        if ($type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\ConstantType) {
            if (\method_exists($type, 'getValue')) {
                return \get_class($type) . $type->getValue();
            }
            throw new \_PhpScoper0a2ac50786fa\PHPStan\ShouldNotHappenException();
        }
        if ($type instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\UnionType) {
            return $this->createUnionTypeHash($type);
        }
        return $this->phpStanStaticTypeMapper->mapToDocString($type);
    }
    public function areTypesEqual(\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $firstType, \_PhpScoper0a2ac50786fa\PHPStan\Type\Type $secondType) : bool
    {
        return $this->createTypeHash($firstType) === $this->createTypeHash($secondType);
    }
    private function resolveUniqueTypeWithClassNameHash(\_PhpScoper0a2ac50786fa\PHPStan\Type\TypeWithClassName $typeWithClassName) : string
    {
        if ($typeWithClassName instanceof \_PhpScoper0a2ac50786fa\Rector\PHPStan\Type\ShortenedObjectType) {
            return $typeWithClassName->getFullyQualifiedName();
        }
        if ($typeWithClassName instanceof \_PhpScoper0a2ac50786fa\Rector\PHPStan\Type\AliasedObjectType) {
            return $typeWithClassName->getFullyQualifiedClass();
        }
        return $typeWithClassName->getClassName();
    }
    private function createUnionTypeHash(\_PhpScoper0a2ac50786fa\PHPStan\Type\UnionType $unionType) : string
    {
        $unionedTypesHashes = [];
        foreach ($unionType->getTypes() as $unionedType) {
            $unionedTypesHashes[] = $this->createTypeHash($unionedType);
        }
        \sort($unionedTypesHashes);
        $unionedTypesHashes = \array_unique($unionedTypesHashes);
        return \implode('|', $unionedTypesHashes);
    }
}
