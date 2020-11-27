<?php

declare (strict_types=1);
namespace PHPStan\Rules\Functions;

use PHPStan\Rules\MissingTypehintCheck;
/**
 * @extends \PHPStan\Testing\RuleTestCase<MissingFunctionParameterTypehintRule>
 */
class MissingFunctionParameterTypehintRuleTest extends \PHPStan\Testing\RuleTestCase
{
    protected function getRule() : \PHPStan\Rules\Rule
    {
        $broker = $this->createReflectionProvider();
        return new \PHPStan\Rules\Functions\MissingFunctionParameterTypehintRule(new \PHPStan\Rules\MissingTypehintCheck($broker, \true, \true));
    }
    public function testRule() : void
    {
        require_once __DIR__ . '/data/missing-function-parameter-typehint.php';
        $this->analyse([__DIR__ . '/data/missing-function-parameter-typehint.php'], [['Function globalFunction() has parameter $b with no typehint specified.', 9], ['Function globalFunction() has parameter $c with no typehint specified.', 9], ['Function MissingFunctionParameterTypehint\\namespacedFunction() has parameter $d with no typehint specified.', 24], ['Function MissingFunctionParameterTypehint\\missingArrayTypehint() has parameter $a with no value type specified in iterable type array.', 36, "Consider adding something like <fg=cyan>array<Foo></> to the PHPDoc.\nYou can turn off this check by setting <fg=cyan>checkMissingIterableValueType: false</> in your <fg=cyan>%configurationFile%</>."], ['Function MissingFunctionParameterTypehint\\missingPhpDocIterableTypehint() has parameter $a with no value type specified in iterable type array.', 44, "Consider adding something like <fg=cyan>array<Foo></> to the PHPDoc.\nYou can turn off this check by setting <fg=cyan>checkMissingIterableValueType: false</> in your <fg=cyan>%configurationFile%</>."], ['Function MissingFunctionParameterTypehint\\unionTypeWithUnknownArrayValueTypehint() has parameter $a with no value type specified in iterable type array.', 60, "Consider adding something like <fg=cyan>array<Foo></> to the PHPDoc.\nYou can turn off this check by setting <fg=cyan>checkMissingIterableValueType: false</> in your <fg=cyan>%configurationFile%</>."], ['Function MissingFunctionParameterTypehint\\acceptsGenericInterface() has parameter $i with generic interface MissingFunctionParameterTypehint\\GenericInterface but does not specify its types: T, U', 111, 'You can turn this off by setting <fg=cyan>checkGenericClassInNonGenericObjectType: false</> in your <fg=cyan>%configurationFile%</>.'], ['Function MissingFunctionParameterTypehint\\acceptsGenericClass() has parameter $c with generic class MissingFunctionParameterTypehint\\GenericClass but does not specify its types: A, B', 130, 'You can turn this off by setting <fg=cyan>checkGenericClassInNonGenericObjectType: false</> in your <fg=cyan>%configurationFile%</>.'], ['Function MissingFunctionParameterTypehint\\missingIterableTypehint() has parameter $iterable with no value type specified in iterable type iterable.', 135, "Consider adding something like <fg=cyan>iterable<Foo></> to the PHPDoc.\nYou can turn off this check by setting <fg=cyan>checkMissingIterableValueType: false</> in your <fg=cyan>%configurationFile%</>."], ['Function MissingFunctionParameterTypehint\\missingIterableTypehintPhpDoc() has parameter $iterable with no value type specified in iterable type iterable.', 143, "Consider adding something like <fg=cyan>iterable<Foo></> to the PHPDoc.\nYou can turn off this check by setting <fg=cyan>checkMissingIterableValueType: false</> in your <fg=cyan>%configurationFile%</>."], ['Function MissingFunctionParameterTypehint\\missingTraversableTypehint() has parameter $traversable with no value type specified in iterable type Traversable.', 148, "Consider adding something like <fg=cyan>Traversable<Foo></> to the PHPDoc.\nYou can turn off this check by setting <fg=cyan>checkMissingIterableValueType: false</> in your <fg=cyan>%configurationFile%</>."], ['Function MissingFunctionParameterTypehint\\missingTraversableTypehintPhpDoc() has parameter $traversable with no value type specified in iterable type Traversable.', 156, "Consider adding something like <fg=cyan>Traversable<Foo></> to the PHPDoc.\nYou can turn off this check by setting <fg=cyan>checkMissingIterableValueType: false</> in your <fg=cyan>%configurationFile%</>."]]);
    }
}
