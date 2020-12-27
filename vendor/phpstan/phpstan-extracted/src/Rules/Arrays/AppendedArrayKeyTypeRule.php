<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Rules\Arrays;

use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use RectorPrefix20201227\PHPStan\Analyser\Scope;
use RectorPrefix20201227\PHPStan\Rules\Properties\PropertyReflectionFinder;
use RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\UnionType;
use PHPStan\Type\VerbosityLevel;
/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\Assign>
 */
class AppendedArrayKeyTypeRule implements \RectorPrefix20201227\PHPStan\Rules\Rule
{
    /** @var PropertyReflectionFinder */
    private $propertyReflectionFinder;
    /** @var bool */
    private $checkUnionTypes;
    public function __construct(\RectorPrefix20201227\PHPStan\Rules\Properties\PropertyReflectionFinder $propertyReflectionFinder, bool $checkUnionTypes)
    {
        $this->propertyReflectionFinder = $propertyReflectionFinder;
        $this->checkUnionTypes = $checkUnionTypes;
    }
    public function getNodeType() : string
    {
        return \PhpParser\Node\Expr\Assign::class;
    }
    public function processNode(\PhpParser\Node $node, \RectorPrefix20201227\PHPStan\Analyser\Scope $scope) : array
    {
        if (!$node->var instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            return [];
        }
        if (!$node->var->var instanceof \PhpParser\Node\Expr\PropertyFetch && !$node->var->var instanceof \PhpParser\Node\Expr\StaticPropertyFetch) {
            return [];
        }
        $propertyReflection = $this->propertyReflectionFinder->findPropertyReflectionFromNode($node->var->var, $scope);
        if ($propertyReflection === null) {
            return [];
        }
        $arrayType = $propertyReflection->getReadableType();
        if (!$arrayType instanceof \PHPStan\Type\ArrayType) {
            return [];
        }
        if ($node->var->dim !== null) {
            $dimensionType = $scope->getType($node->var->dim);
            $isValidKey = \RectorPrefix20201227\PHPStan\Rules\Arrays\AllowedArrayKeysTypes::getType()->isSuperTypeOf($dimensionType);
            if (!$isValidKey->yes()) {
                // already handled by InvalidKeyInArrayDimFetchRule
                return [];
            }
            $keyType = \PHPStan\Type\ArrayType::castToArrayKeyType($dimensionType);
            if (!$this->checkUnionTypes && $keyType instanceof \PHPStan\Type\UnionType) {
                return [];
            }
        } else {
            $keyType = new \PHPStan\Type\IntegerType();
        }
        if (!$arrayType->getIterableKeyType()->isSuperTypeOf($keyType)->yes()) {
            return [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Array (%s) does not accept key %s.', $arrayType->describe(\PHPStan\Type\VerbosityLevel::typeOnly()), $keyType->describe(\PHPStan\Type\VerbosityLevel::value())))->build()];
        }
        return [];
    }
}
