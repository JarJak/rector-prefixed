<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Type\Php;

use _PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall;
use _PhpScopere8e811afab72\PHPStan\Analyser\Scope;
use _PhpScopere8e811afab72\PHPStan\Reflection\FunctionReflection;
use _PhpScopere8e811afab72\PHPStan\Reflection\ParametersAcceptorSelector;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantBooleanType;
use _PhpScopere8e811afab72\PHPStan\Type\DynamicFunctionReturnTypeExtension;
use _PhpScopere8e811afab72\PHPStan\Type\MixedType;
use _PhpScopere8e811afab72\PHPStan\Type\StringType;
use _PhpScopere8e811afab72\PHPStan\Type\Type;
use _PhpScopere8e811afab72\PHPStan\Type\TypeUtils;
final class HashHmacFunctionsReturnTypeExtension implements \_PhpScopere8e811afab72\PHPStan\Type\DynamicFunctionReturnTypeExtension
{
    private const HMAC_ALGORITHMS = ['md2', 'md4', 'md5', 'sha1', 'sha224', 'sha256', 'sha384', 'sha512/224', 'sha512/256', 'sha512', 'sha3-224', 'sha3-256', 'sha3-384', 'sha3-512', 'ripemd128', 'ripemd160', 'ripemd256', 'ripemd320', 'whirlpool', 'tiger128,3', 'tiger160,3', 'tiger192,3', 'tiger128,4', 'tiger160,4', 'tiger192,4', 'snefru', 'snefru256', 'gost', 'gost-crypto', 'haval128,3', 'haval160,3', 'haval192,3', 'haval224,3', 'haval256,3', 'haval128,4', 'haval160,4', 'haval192,4', 'haval224,4', 'haval256,4', 'haval128,5', 'haval160,5', 'haval192,5', 'haval224,5', 'haval256,5'];
    public function isFunctionSupported(\_PhpScopere8e811afab72\PHPStan\Reflection\FunctionReflection $functionReflection) : bool
    {
        return \in_array($functionReflection->getName(), ['hash_hmac', 'hash_hmac_file'], \true);
    }
    public function getTypeFromFunctionCall(\_PhpScopere8e811afab72\PHPStan\Reflection\FunctionReflection $functionReflection, \_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall $functionCall, \_PhpScopere8e811afab72\PHPStan\Analyser\Scope $scope) : \_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        if ($functionReflection->getName() === 'hash_hmac') {
            $defaultReturnType = new \_PhpScopere8e811afab72\PHPStan\Type\StringType();
        } else {
            $defaultReturnType = \_PhpScopere8e811afab72\PHPStan\Reflection\ParametersAcceptorSelector::selectSingle($functionReflection->getVariants())->getReturnType();
        }
        if (!isset($functionCall->args[0])) {
            return $defaultReturnType;
        }
        $argType = $scope->getType($functionCall->args[0]->value);
        if ($argType instanceof \_PhpScopere8e811afab72\PHPStan\Type\MixedType) {
            return \_PhpScopere8e811afab72\PHPStan\Type\TypeUtils::toBenevolentUnion($defaultReturnType);
        }
        $values = \_PhpScopere8e811afab72\PHPStan\Type\TypeUtils::getConstantStrings($argType);
        if (\count($values) !== 1) {
            return \_PhpScopere8e811afab72\PHPStan\Type\TypeUtils::toBenevolentUnion($defaultReturnType);
        }
        $string = $values[0];
        return \in_array($string->getValue(), self::HMAC_ALGORITHMS, \true) ? $defaultReturnType : new \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantBooleanType(\false);
    }
}