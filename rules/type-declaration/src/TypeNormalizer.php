<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration;

use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\NodeTypeResolver\PHPStan\TypeHasher;
use Rector\StaticTypeMapper\TypeFactory\TypeFactoryStaticHelper;
use Rector\TypeDeclaration\ValueObject\NestedArrayType;
/**
 * @see \Rector\TypeDeclaration\Tests\TypeNormalizerTest
 */
final class TypeNormalizer
{
    /**
     * @var NestedArrayType[]
     */
    private $collectedNestedArrayTypes = [];
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    /**
     * @var TypeHasher
     */
    private $typeHasher;
    public function __construct(\Rector\NodeTypeResolver\PHPStan\Type\TypeFactory $typeFactory, \Rector\NodeTypeResolver\PHPStan\TypeHasher $typeHasher)
    {
        $this->typeFactory = $typeFactory;
        $this->typeHasher = $typeHasher;
    }
    public function convertConstantArrayTypeToArrayType(\PHPStan\Type\Constant\ConstantArrayType $constantArrayType) : ?\PHPStan\Type\ArrayType
    {
        $nonConstantValueTypes = [];
        if ($constantArrayType->getItemType() instanceof \PHPStan\Type\UnionType) {
            /** @var UnionType $unionType */
            $unionType = $constantArrayType->getItemType();
            foreach ($unionType->getTypes() as $unionedType) {
                if ($unionedType instanceof \PHPStan\Type\Constant\ConstantStringType) {
                    $stringType = new \PHPStan\Type\StringType();
                    $nonConstantValueTypes[\get_class($stringType)] = $stringType;
                } elseif ($unionedType instanceof \PHPStan\Type\ObjectType) {
                    $nonConstantValueTypes[] = $unionedType;
                } else {
                    return null;
                }
            }
        } else {
            return null;
        }
        return $this->createArrayTypeFromNonConstantValueTypes($nonConstantValueTypes);
    }
    /**
     * Turn nested array union types to unique ones:
     * e.g. int[]|string[][]|bool[][]|string[][]
     * ↓
     * int[]|string[][]|bool[][]
     */
    public function normalizeArrayOfUnionToUnionArray(\PHPStan\Type\Type $type, int $arrayNesting = 1) : \PHPStan\Type\Type
    {
        if (!$type instanceof \PHPStan\Type\ArrayType) {
            return $type;
        }
        // first collection of types
        if ($arrayNesting === 1) {
            $this->collectedNestedArrayTypes = [];
        }
        if ($type->getItemType() instanceof \PHPStan\Type\ArrayType) {
            ++$arrayNesting;
            $this->normalizeArrayOfUnionToUnionArray($type->getItemType(), $arrayNesting);
        } elseif ($type->getItemType() instanceof \PHPStan\Type\UnionType) {
            $this->collectNestedArrayTypeFromUnionType($type->getItemType(), $arrayNesting);
        } else {
            $this->collectedNestedArrayTypes[] = new \Rector\TypeDeclaration\ValueObject\NestedArrayType($type->getItemType(), $arrayNesting, $type->getKeyType());
        }
        return $this->createUnionedTypesFromArrayTypes($this->collectedNestedArrayTypes);
    }
    public function uniqueateConstantArrayType(\PHPStan\Type\Type $type) : \PHPStan\Type\Type
    {
        if (!$type instanceof \PHPStan\Type\Constant\ConstantArrayType) {
            return $type;
        }
        // nothing to normalize
        if ($type->getValueTypes() === []) {
            return $type;
        }
        $uniqueTypes = [];
        $removedKeys = [];
        foreach ($type->getValueTypes() as $key => $valueType) {
            $typeHash = $this->typeHasher->createTypeHash($valueType);
            $valueType = $this->uniqueateConstantArrayType($valueType);
            $valueType = $this->normalizeArrayOfUnionToUnionArray($valueType);
            if (!isset($uniqueTypes[$typeHash])) {
                $uniqueTypes[$typeHash] = $valueType;
            } else {
                $removedKeys[] = $key;
            }
        }
        // re-index keys
        $uniqueTypes = \array_values($uniqueTypes);
        $keyTypes = [];
        foreach ($type->getKeyTypes() as $key => $keyType) {
            if (\in_array($key, $removedKeys, \true)) {
                // remove it
                continue;
            }
            $keyTypes[$key] = $keyType;
        }
        return new \PHPStan\Type\Constant\ConstantArrayType($keyTypes, $uniqueTypes);
    }
    /**
     * From "string[]|mixed[]" based on empty array to to "string[]"
     */
    public function normalizeArrayTypeAndArrayNever(\PHPStan\Type\Type $type) : \PHPStan\Type\Type
    {
        if (!$type instanceof \PHPStan\Type\UnionType) {
            return $type;
        }
        $nonNeverTypes = [];
        foreach ($type->getTypes() as $unionedType) {
            if (!$unionedType instanceof \PHPStan\Type\ArrayType) {
                return $type;
            }
            if ($unionedType->getItemType() instanceof \PHPStan\Type\NeverType) {
                continue;
            }
            $nonNeverTypes[] = $unionedType;
        }
        return $this->typeFactory->createMixedPassedOrUnionType($nonNeverTypes);
    }
    /**
     * @param array<string|int, Type> $nonConstantValueTypes
     */
    private function createArrayTypeFromNonConstantValueTypes(array $nonConstantValueTypes) : \PHPStan\Type\ArrayType
    {
        $nonConstantValueTypes = \array_values($nonConstantValueTypes);
        if (\count($nonConstantValueTypes) > 1) {
            $nonConstantValueType = \Rector\StaticTypeMapper\TypeFactory\TypeFactoryStaticHelper::createUnionObjectType($nonConstantValueTypes);
        } else {
            $nonConstantValueType = $nonConstantValueTypes[0];
        }
        return new \PHPStan\Type\ArrayType(new \PHPStan\Type\MixedType(), $nonConstantValueType);
    }
    private function collectNestedArrayTypeFromUnionType(\PHPStan\Type\UnionType $unionType, int $arrayNesting) : void
    {
        foreach ($unionType->getTypes() as $unionedType) {
            if ($unionedType instanceof \PHPStan\Type\ArrayType) {
                ++$arrayNesting;
                $this->normalizeArrayOfUnionToUnionArray($unionedType, $arrayNesting);
            } else {
                $this->collectedNestedArrayTypes[] = new \Rector\TypeDeclaration\ValueObject\NestedArrayType($unionedType, $arrayNesting);
            }
        }
    }
    /**
     * @param NestedArrayType[] $collectedNestedArrayTypes
     */
    private function createUnionedTypesFromArrayTypes(array $collectedNestedArrayTypes) : \PHPStan\Type\Type
    {
        $unionedTypes = [];
        foreach ($collectedNestedArrayTypes as $collectedNestedArrayType) {
            $arrayType = $collectedNestedArrayType->getType();
            for ($i = 0; $i < $collectedNestedArrayType->getArrayNestingLevel(); ++$i) {
                $arrayType = new \PHPStan\Type\ArrayType($collectedNestedArrayType->getKeyType(), $arrayType);
            }
            /** @var ArrayType $arrayType */
            $unionedTypes[] = $arrayType;
        }
        if (\count($unionedTypes) > 1) {
            return \Rector\StaticTypeMapper\TypeFactory\TypeFactoryStaticHelper::createUnionObjectType($unionedTypes);
        }
        return $unionedTypes[0];
    }
}
