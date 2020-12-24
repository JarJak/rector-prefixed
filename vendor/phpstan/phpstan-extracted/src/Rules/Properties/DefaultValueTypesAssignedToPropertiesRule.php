<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Rules\Properties;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PHPStan\Analyser\Scope;
use _PhpScopere8e811afab72\PHPStan\Node\ClassPropertyNode;
use _PhpScopere8e811afab72\PHPStan\Rules\RuleErrorBuilder;
use _PhpScopere8e811afab72\PHPStan\Rules\RuleLevelHelper;
use _PhpScopere8e811afab72\PHPStan\Type\MixedType;
use _PhpScopere8e811afab72\PHPStan\Type\VerbosityLevel;
/**
 * @implements \PHPStan\Rules\Rule<\PHPStan\Node\ClassPropertyNode>
 */
class DefaultValueTypesAssignedToPropertiesRule implements \_PhpScopere8e811afab72\PHPStan\Rules\Rule
{
    /** @var \PHPStan\Rules\RuleLevelHelper */
    private $ruleLevelHelper;
    public function __construct(\_PhpScopere8e811afab72\PHPStan\Rules\RuleLevelHelper $ruleLevelHelper)
    {
        $this->ruleLevelHelper = $ruleLevelHelper;
    }
    public function getNodeType() : string
    {
        return \_PhpScopere8e811afab72\PHPStan\Node\ClassPropertyNode::class;
    }
    public function processNode(\_PhpScopere8e811afab72\PhpParser\Node $node, \_PhpScopere8e811afab72\PHPStan\Analyser\Scope $scope) : array
    {
        if (!$scope->isInClass()) {
            throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException();
        }
        $classReflection = $scope->getClassReflection();
        $default = $node->getDefault();
        if ($default === null) {
            return [];
        }
        $propertyReflection = $classReflection->getNativeProperty($node->getName());
        $propertyType = $propertyReflection->getWritableType();
        if ($propertyReflection->getNativeType() instanceof \_PhpScopere8e811afab72\PHPStan\Type\MixedType) {
            if ($default instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\ConstFetch && (string) $default->name === 'null') {
                return [];
            }
        }
        $defaultValueType = $scope->getType($default);
        if ($this->ruleLevelHelper->accepts($propertyType, $defaultValueType, \true)) {
            return [];
        }
        $verbosityLevel = \_PhpScopere8e811afab72\PHPStan\Type\VerbosityLevel::getRecommendedLevelByType($propertyType);
        return [\_PhpScopere8e811afab72\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('%s %s::$%s (%s) does not accept default value of type %s.', $node->isStatic() ? 'Static property' : 'Property', $classReflection->getDisplayName(), $node->getName(), $propertyType->describe($verbosityLevel), $defaultValueType->describe($verbosityLevel)))->build()];
    }
}