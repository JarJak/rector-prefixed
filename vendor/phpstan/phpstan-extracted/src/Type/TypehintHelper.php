<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Type;

use _PhpScopere8e811afab72\PHPStan\Broker\Broker;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantBooleanType;
use _PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeHelper;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;
class TypehintHelper
{
    private static function getTypeObjectFromTypehint(string $typeString, ?string $selfClass) : \_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        switch (\strtolower($typeString)) {
            case 'int':
                return new \_PhpScopere8e811afab72\PHPStan\Type\IntegerType();
            case 'bool':
                return new \_PhpScopere8e811afab72\PHPStan\Type\BooleanType();
            case 'false':
                return new \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantBooleanType(\false);
            case 'string':
                return new \_PhpScopere8e811afab72\PHPStan\Type\StringType();
            case 'float':
                return new \_PhpScopere8e811afab72\PHPStan\Type\FloatType();
            case 'array':
                return new \_PhpScopere8e811afab72\PHPStan\Type\ArrayType(new \_PhpScopere8e811afab72\PHPStan\Type\MixedType(), new \_PhpScopere8e811afab72\PHPStan\Type\MixedType());
            case 'iterable':
                return new \_PhpScopere8e811afab72\PHPStan\Type\IterableType(new \_PhpScopere8e811afab72\PHPStan\Type\MixedType(), new \_PhpScopere8e811afab72\PHPStan\Type\MixedType());
            case 'callable':
                return new \_PhpScopere8e811afab72\PHPStan\Type\CallableType();
            case 'void':
                return new \_PhpScopere8e811afab72\PHPStan\Type\VoidType();
            case 'object':
                return new \_PhpScopere8e811afab72\PHPStan\Type\ObjectWithoutClassType();
            case 'mixed':
                return new \_PhpScopere8e811afab72\PHPStan\Type\MixedType(\true);
            case 'self':
                return $selfClass !== null ? new \_PhpScopere8e811afab72\PHPStan\Type\ObjectType($selfClass) : new \_PhpScopere8e811afab72\PHPStan\Type\ErrorType();
            case 'parent':
                $broker = \_PhpScopere8e811afab72\PHPStan\Broker\Broker::getInstance();
                if ($selfClass !== null && $broker->hasClass($selfClass)) {
                    $classReflection = $broker->getClass($selfClass);
                    if ($classReflection->getParentClass() !== \false) {
                        return new \_PhpScopere8e811afab72\PHPStan\Type\ObjectType($classReflection->getParentClass()->getName());
                    }
                }
                return new \_PhpScopere8e811afab72\PHPStan\Type\NonexistentParentClassType();
            case 'static':
                return $selfClass !== null ? new \_PhpScopere8e811afab72\PHPStan\Type\StaticType($selfClass) : new \_PhpScopere8e811afab72\PHPStan\Type\ErrorType();
            case 'null':
                return new \_PhpScopere8e811afab72\PHPStan\Type\NullType();
            default:
                return new \_PhpScopere8e811afab72\PHPStan\Type\ObjectType($typeString);
        }
    }
    public static function decideTypeFromReflection(?\ReflectionType $reflectionType, ?\_PhpScopere8e811afab72\PHPStan\Type\Type $phpDocType = null, ?string $selfClass = null, bool $isVariadic = \false) : \_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        if ($reflectionType === null) {
            if ($isVariadic && $phpDocType instanceof \_PhpScopere8e811afab72\PHPStan\Type\ArrayType) {
                $phpDocType = $phpDocType->getItemType();
            }
            return $phpDocType ?? new \_PhpScopere8e811afab72\PHPStan\Type\MixedType();
        }
        if ($reflectionType instanceof \ReflectionUnionType) {
            $type = \_PhpScopere8e811afab72\PHPStan\Type\TypeCombinator::union(...\array_map(static function (\ReflectionType $type) use($selfClass) : Type {
                return self::decideTypeFromReflection($type, null, $selfClass, \false);
            }, $reflectionType->getTypes()));
            return self::decideType($type, $phpDocType);
        }
        if (!$reflectionType instanceof \ReflectionNamedType) {
            throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException(\sprintf('Unexpected type: %s', \get_class($reflectionType)));
        }
        $reflectionTypeString = $reflectionType->getName();
        if (\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\Strings::endsWith(\strtolower($reflectionTypeString), '\\object')) {
            $reflectionTypeString = 'object';
        }
        if (\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\Strings::endsWith(\strtolower($reflectionTypeString), '\\mixed')) {
            $reflectionTypeString = 'mixed';
        }
        if (\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\Strings::endsWith(\strtolower($reflectionTypeString), '\\false')) {
            $reflectionTypeString = 'false';
        }
        if (\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\Strings::endsWith(\strtolower($reflectionTypeString), '\\null')) {
            $reflectionTypeString = 'null';
        }
        $type = self::getTypeObjectFromTypehint($reflectionTypeString, $selfClass);
        if ($reflectionType->allowsNull()) {
            $type = \_PhpScopere8e811afab72\PHPStan\Type\TypeCombinator::addNull($type);
        } elseif ($phpDocType !== null) {
            $phpDocType = \_PhpScopere8e811afab72\PHPStan\Type\TypeCombinator::removeNull($phpDocType);
        }
        return self::decideType($type, $phpDocType);
    }
    public static function decideType(\_PhpScopere8e811afab72\PHPStan\Type\Type $type, ?\_PhpScopere8e811afab72\PHPStan\Type\Type $phpDocType = null) : \_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        if ($phpDocType !== null && !$phpDocType instanceof \_PhpScopere8e811afab72\PHPStan\Type\ErrorType) {
            if ($type instanceof \_PhpScopere8e811afab72\PHPStan\Type\VoidType) {
                if ($phpDocType instanceof \_PhpScopere8e811afab72\PHPStan\Type\NeverType && $phpDocType->isExplicit()) {
                    return $phpDocType;
                }
                return new \_PhpScopere8e811afab72\PHPStan\Type\VoidType();
            }
            if ($type instanceof \_PhpScopere8e811afab72\PHPStan\Type\MixedType && !$type->isExplicitMixed() && $phpDocType instanceof \_PhpScopere8e811afab72\PHPStan\Type\VoidType) {
                return $phpDocType;
            }
            if (\_PhpScopere8e811afab72\PHPStan\Type\TypeCombinator::removeNull($type) instanceof \_PhpScopere8e811afab72\PHPStan\Type\IterableType) {
                if ($phpDocType instanceof \_PhpScopere8e811afab72\PHPStan\Type\UnionType) {
                    $innerTypes = [];
                    foreach ($phpDocType->getTypes() as $innerType) {
                        if ($innerType instanceof \_PhpScopere8e811afab72\PHPStan\Type\ArrayType) {
                            $innerTypes[] = new \_PhpScopere8e811afab72\PHPStan\Type\IterableType($innerType->getKeyType(), $innerType->getItemType());
                        } else {
                            $innerTypes[] = $innerType;
                        }
                    }
                    $phpDocType = new \_PhpScopere8e811afab72\PHPStan\Type\UnionType($innerTypes);
                } elseif ($phpDocType instanceof \_PhpScopere8e811afab72\PHPStan\Type\ArrayType) {
                    $phpDocType = new \_PhpScopere8e811afab72\PHPStan\Type\IterableType($phpDocType->getKeyType(), $phpDocType->getItemType());
                }
            }
            $resultType = $type->isSuperTypeOf(\_PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeHelper::resolveToBounds($phpDocType))->yes() ? $phpDocType : $type;
            if ($type instanceof \_PhpScopere8e811afab72\PHPStan\Type\UnionType) {
                $addToUnionTypes = [];
                foreach ($type->getTypes() as $innerType) {
                    if (!$innerType->isSuperTypeOf($resultType)->no()) {
                        continue;
                    }
                    $addToUnionTypes[] = $innerType;
                }
                if (\count($addToUnionTypes) > 0) {
                    $type = \_PhpScopere8e811afab72\PHPStan\Type\TypeCombinator::union($resultType, ...$addToUnionTypes);
                } else {
                    $type = $resultType;
                }
            } elseif (\_PhpScopere8e811afab72\PHPStan\Type\TypeCombinator::containsNull($type)) {
                $type = \_PhpScopere8e811afab72\PHPStan\Type\TypeCombinator::addNull($resultType);
            } else {
                $type = $resultType;
            }
        }
        return $type;
    }
}