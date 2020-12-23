<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Type\Php;

use _PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\FunctionReflection;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Constant\ConstantBooleanType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Constant\ConstantIntegerType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ErrorType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\StringType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
use _PhpScoper0a2ac50786fa\PHPStan\Type\UnionType;
class StrWordCountFunctionDynamicReturnTypeExtension implements \_PhpScoper0a2ac50786fa\PHPStan\Type\DynamicFunctionReturnTypeExtension
{
    public function isFunctionSupported(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\FunctionReflection $functionReflection) : bool
    {
        return $functionReflection->getName() === 'str_word_count';
    }
    public function getTypeFromFunctionCall(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\FunctionReflection $functionReflection, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\FuncCall $functionCall, \_PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope $scope) : \_PhpScoper0a2ac50786fa\PHPStan\Type\Type
    {
        $argsCount = \count($functionCall->args);
        if ($argsCount === 1) {
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType();
        } elseif ($argsCount === 2 || $argsCount === 3) {
            $formatType = $scope->getType($functionCall->args[1]->value);
            if ($formatType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\Constant\ConstantIntegerType) {
                $val = $formatType->getValue();
                if ($val === 0) {
                    // return word count
                    return new \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType();
                } elseif ($val === 1 || $val === 2) {
                    // return [word] or [offset => word]
                    return new \_PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType(new \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType(), new \_PhpScoper0a2ac50786fa\PHPStan\Type\StringType());
                }
                // return false, invalid format value specified
                return new \_PhpScoper0a2ac50786fa\PHPStan\Type\Constant\ConstantBooleanType(\false);
            }
            // Could be invalid format type as well, but parameter type checks will catch that.
            return new \_PhpScoper0a2ac50786fa\PHPStan\Type\UnionType([new \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType(), new \_PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType(new \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType(), new \_PhpScoper0a2ac50786fa\PHPStan\Type\StringType()), new \_PhpScoper0a2ac50786fa\PHPStan\Type\Constant\ConstantBooleanType(\false)]);
        }
        // else fatal error; too many or too few arguments
        return new \_PhpScoper0a2ac50786fa\PHPStan\Type\ErrorType();
    }
}
