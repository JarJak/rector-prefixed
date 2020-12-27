<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Rules\Comparison;

use PhpParser\Node\Expr\BinaryOp;
use RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\VerbosityLevel;
/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\BinaryOp>
 */
class NumberComparisonOperatorsConstantConditionRule implements \RectorPrefix20201227\PHPStan\Rules\Rule
{
    public function getNodeType() : string
    {
        return \PhpParser\Node\Expr\BinaryOp::class;
    }
    public function processNode(\PhpParser\Node $node, \RectorPrefix20201227\PHPStan\Analyser\Scope $scope) : array
    {
        if (!$node instanceof \PhpParser\Node\Expr\BinaryOp\Greater && !$node instanceof \PhpParser\Node\Expr\BinaryOp\GreaterOrEqual && !$node instanceof \PhpParser\Node\Expr\BinaryOp\Smaller && !$node instanceof \PhpParser\Node\Expr\BinaryOp\SmallerOrEqual) {
            return [];
        }
        $exprType = $scope->getType($node);
        if ($exprType instanceof \PHPStan\Type\Constant\ConstantBooleanType) {
            return [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Comparison operation "%s" between %s and %s is always %s.', $node->getOperatorSigil(), $scope->getType($node->left)->describe(\PHPStan\Type\VerbosityLevel::value()), $scope->getType($node->right)->describe(\PHPStan\Type\VerbosityLevel::value()), $exprType->getValue() ? 'true' : 'false'))->build()];
        }
        return [];
    }
}
