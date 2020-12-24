<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Rules;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PHPStan\Analyser\Scope;
use _PhpScopere8e811afab72\PHPStan\Reflection\ReflectionProvider;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantStringType;
class UnusedFunctionParametersCheck
{
    /** @var ReflectionProvider */
    private $reflectionProvider;
    public function __construct(\_PhpScopere8e811afab72\PHPStan\Reflection\ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }
    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @param string[] $parameterNames
     * @param \PhpParser\Node[] $statements
     * @param string $unusedParameterMessage
     * @param string $identifier
     * @param mixed[] $additionalMetadata
     * @return RuleError[]
     */
    public function getUnusedParameters(\_PhpScopere8e811afab72\PHPStan\Analyser\Scope $scope, array $parameterNames, array $statements, string $unusedParameterMessage, string $identifier, array $additionalMetadata) : array
    {
        $unusedParameters = \array_fill_keys($parameterNames, \true);
        foreach ($this->getUsedVariables($scope, $statements) as $variableName) {
            if (!isset($unusedParameters[$variableName])) {
                continue;
            }
            unset($unusedParameters[$variableName]);
        }
        $errors = [];
        foreach (\array_keys($unusedParameters) as $name) {
            $errors[] = \_PhpScopere8e811afab72\PHPStan\Rules\RuleErrorBuilder::message(\sprintf($unusedParameterMessage, $name))->identifier($identifier)->metadata($additionalMetadata + ['variableName' => $name])->build();
        }
        return $errors;
    }
    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @param \PhpParser\Node[]|\PhpParser\Node|scalar $node
     * @return string[]
     */
    private function getUsedVariables(\_PhpScopere8e811afab72\PHPStan\Analyser\Scope $scope, $node) : array
    {
        $variableNames = [];
        if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node) {
            if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall && $node->name instanceof \_PhpScopere8e811afab72\PhpParser\Node\Name) {
                $functionName = $this->reflectionProvider->resolveFunctionName($node->name, $scope);
                if ($functionName === 'func_get_args') {
                    return $scope->getDefinedVariables();
                }
            }
            if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable && \is_string($node->name) && $node->name !== 'this') {
                return [$node->name];
            }
            if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\ClosureUse && \is_string($node->var->name)) {
                return [$node->var->name];
            }
            if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall && $node->name instanceof \_PhpScopere8e811afab72\PhpParser\Node\Name && (string) $node->name === 'compact') {
                foreach ($node->args as $arg) {
                    $argType = $scope->getType($arg->value);
                    if (!$argType instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantStringType) {
                        continue;
                    }
                    $variableNames[] = $argType->getValue();
                }
            }
            foreach ($node->getSubNodeNames() as $subNodeName) {
                if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Closure && $subNodeName !== 'uses') {
                    continue;
                }
                $subNode = $node->{$subNodeName};
                $variableNames = \array_merge($variableNames, $this->getUsedVariables($scope, $subNode));
            }
        } elseif (\is_array($node)) {
            foreach ($node as $subNode) {
                $variableNames = \array_merge($variableNames, $this->getUsedVariables($scope, $subNode));
            }
        }
        return $variableNames;
    }
}