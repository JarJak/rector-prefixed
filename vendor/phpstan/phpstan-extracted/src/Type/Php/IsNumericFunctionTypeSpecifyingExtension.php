<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Type\Php;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\FuncCall;
use _PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope;
use _PhpScoper0a2ac50786fa\PHPStan\Analyser\SpecifiedTypes;
use _PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifier;
use _PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifierAwareExtension;
use _PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifierContext;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\FunctionReflection;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Accessory\AccessoryNumericStringType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\FloatType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\FunctionTypeSpecifyingExtension;
use _PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\IntersectionType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\StringType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\UnionType;
class IsNumericFunctionTypeSpecifyingExtension implements \_PhpScoper0a2ac50786fa\PHPStan\Type\FunctionTypeSpecifyingExtension, \_PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifierAwareExtension
{
    /** @var \PHPStan\Analyser\TypeSpecifier */
    private $typeSpecifier;
    public function isFunctionSupported(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\FunctionReflection $functionReflection, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\FuncCall $node, \_PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifierContext $context) : bool
    {
        return $functionReflection->getName() === 'is_numeric' && isset($node->args[0]) && !$context->null();
    }
    public function specifyTypes(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\FunctionReflection $functionReflection, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\FuncCall $node, \_PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope $scope, \_PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifierContext $context) : \_PhpScoper0a2ac50786fa\PHPStan\Analyser\SpecifiedTypes
    {
        if ($context->null()) {
            throw new \_PhpScoper0a2ac50786fa\PHPStan\ShouldNotHappenException();
        }
        $numericTypes = [new \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType(), new \_PhpScoper0a2ac50786fa\PHPStan\Type\FloatType()];
        if ($context->truthy()) {
            $numericTypes[] = new \_PhpScoper0a2ac50786fa\PHPStan\Type\IntersectionType([new \_PhpScoper0a2ac50786fa\PHPStan\Type\StringType(), new \_PhpScoper0a2ac50786fa\PHPStan\Type\Accessory\AccessoryNumericStringType()]);
        }
        return $this->typeSpecifier->create($node->args[0]->value, new \_PhpScoper0a2ac50786fa\PHPStan\Type\UnionType($numericTypes), $context);
    }
    public function setTypeSpecifier(\_PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifier $typeSpecifier) : void
    {
        $this->typeSpecifier = $typeSpecifier;
    }
}
