<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Rules\Arrays;

use _PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope;
use _PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrorBuilder;
use _PhpScoper0a2ac50786fa\PHPStan\Type\MixedType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\TypeUtils;
use _PhpScoper0a2ac50786fa\PHPStan\Type\VerbosityLevel;
/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\ArrayDimFetch>
 */
class InvalidKeyInArrayDimFetchRule implements \_PhpScoper0a2ac50786fa\PHPStan\Rules\Rule
{
    /** @var bool */
    private $reportMaybes;
    public function __construct(bool $reportMaybes)
    {
        $this->reportMaybes = $reportMaybes;
    }
    public function getNodeType() : string
    {
        return \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayDimFetch::class;
    }
    public function processNode(\_PhpScoper0a2ac50786fa\PhpParser\Node $node, \_PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope $scope) : array
    {
        if ($node->dim === null) {
            return [];
        }
        $varType = $scope->getType($node->var);
        if (\count(\_PhpScoper0a2ac50786fa\PHPStan\Type\TypeUtils::getArrays($varType)) === 0) {
            return [];
        }
        $dimensionType = $scope->getType($node->dim);
        $isSuperType = \_PhpScoper0a2ac50786fa\PHPStan\Rules\Arrays\AllowedArrayKeysTypes::getType()->isSuperTypeOf($dimensionType);
        if ($isSuperType->no()) {
            return [\_PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Invalid array key type %s.', $dimensionType->describe(\_PhpScoper0a2ac50786fa\PHPStan\Type\VerbosityLevel::typeOnly())))->build()];
        } elseif ($this->reportMaybes && $isSuperType->maybe() && !$dimensionType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType) {
            return [\_PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Possibly invalid array key type %s.', $dimensionType->describe(\_PhpScoper0a2ac50786fa\PHPStan\Type\VerbosityLevel::typeOnly())))->build()];
        }
        return [];
    }
}
