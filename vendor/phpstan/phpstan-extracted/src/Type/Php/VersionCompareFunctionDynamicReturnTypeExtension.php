<?php

declare (strict_types=1);
namespace PHPStan\Type\Php;

use PhpParser\Node\Expr\FuncCall;
use RectorPrefix20201227\PHPStan\Analyser\Scope;
use RectorPrefix20201227\PHPStan\Reflection\FunctionReflection;
use RectorPrefix20201227\PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeUtils;
class VersionCompareFunctionDynamicReturnTypeExtension implements \PHPStan\Type\DynamicFunctionReturnTypeExtension
{
    public function isFunctionSupported(\RectorPrefix20201227\PHPStan\Reflection\FunctionReflection $functionReflection) : bool
    {
        return $functionReflection->getName() === 'version_compare';
    }
    public function getTypeFromFunctionCall(\RectorPrefix20201227\PHPStan\Reflection\FunctionReflection $functionReflection, \PhpParser\Node\Expr\FuncCall $functionCall, \RectorPrefix20201227\PHPStan\Analyser\Scope $scope) : \PHPStan\Type\Type
    {
        if (\count($functionCall->args) < 2) {
            return \RectorPrefix20201227\PHPStan\Reflection\ParametersAcceptorSelector::selectFromArgs($scope, $functionCall->args, $functionReflection->getVariants())->getReturnType();
        }
        $version1Strings = \PHPStan\Type\TypeUtils::getConstantStrings($scope->getType($functionCall->args[0]->value));
        $version2Strings = \PHPStan\Type\TypeUtils::getConstantStrings($scope->getType($functionCall->args[1]->value));
        $counts = [\count($version1Strings), \count($version2Strings)];
        if (isset($functionCall->args[2])) {
            $operatorStrings = \PHPStan\Type\TypeUtils::getConstantStrings($scope->getType($functionCall->args[2]->value));
            $counts[] = \count($operatorStrings);
            $returnType = new \PHPStan\Type\BooleanType();
        } else {
            $returnType = \PHPStan\Type\TypeCombinator::union(new \PHPStan\Type\Constant\ConstantIntegerType(-1), new \PHPStan\Type\Constant\ConstantIntegerType(0), new \PHPStan\Type\Constant\ConstantIntegerType(1));
        }
        if (\count(\array_filter($counts, static function (int $count) : bool {
            return $count === 0;
        })) > 0) {
            return $returnType;
            // one of the arguments is not a constant string
        }
        if (\count(\array_filter($counts, static function (int $count) : bool {
            return $count > 1;
        })) > 1) {
            return $returnType;
            // more than one argument can have multiple possibilities, avoid combinatorial explosion
        }
        $types = [];
        foreach ($version1Strings as $version1String) {
            foreach ($version2Strings as $version2String) {
                if (isset($operatorStrings)) {
                    foreach ($operatorStrings as $operatorString) {
                        $value = \version_compare($version1String->getValue(), $version2String->getValue(), $operatorString->getValue());
                        $types[$value] = new \PHPStan\Type\Constant\ConstantBooleanType($value);
                    }
                } else {
                    $value = \version_compare($version1String->getValue(), $version2String->getValue());
                    $types[$value] = new \PHPStan\Type\Constant\ConstantIntegerType($value);
                }
            }
        }
        return \PHPStan\Type\TypeCombinator::union(...$types);
    }
}
