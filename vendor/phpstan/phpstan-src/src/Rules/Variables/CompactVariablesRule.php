<?php

declare (strict_types=1);
namespace PHPStan\Rules\Variables;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantStringType;
/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\FuncCall>
 */
final class CompactVariablesRule implements \PHPStan\Rules\Rule
{
    /**
     * @var bool
     */
    private $checkMaybeUndefinedVariables;
    public function __construct(bool $checkMaybeUndefinedVariables)
    {
        $this->checkMaybeUndefinedVariables = $checkMaybeUndefinedVariables;
    }
    public function getNodeType() : string
    {
        return \PhpParser\Node\Expr\FuncCall::class;
    }
    public function processNode(\PhpParser\Node $node, \PHPStan\Analyser\Scope $scope) : array
    {
        if ($node->name instanceof \PhpParser\Node\Expr) {
            return [];
        }
        $functionName = \strtolower($node->name->toString());
        if ($functionName !== 'compact') {
            return [];
        }
        $functionArguments = $node->args;
        $messages = [];
        foreach ($functionArguments as $argument) {
            $argumentType = $scope->getType($argument->value);
            if (!$argumentType instanceof \PHPStan\Type\Constant\ConstantStringType) {
                continue;
            }
            $variableName = $argumentType->getValue();
            $scopeHasVariable = $scope->hasVariableType($variableName);
            if ($scopeHasVariable->no()) {
                $messages[] = \PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Call to function compact() contains undefined variable $%s.', $variableName))->line($argument->getLine())->build();
            } elseif ($this->checkMaybeUndefinedVariables && $scopeHasVariable->maybe()) {
                $messages[] = \PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Call to function compact() contains possibly undefined variable $%s.', $variableName))->line($argument->getLine())->build();
            }
        }
        return $messages;
    }
}