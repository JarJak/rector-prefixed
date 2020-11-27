<?php

declare (strict_types=1);
namespace PHPStan\Rules\Functions;

/**
 * @extends \PHPStan\Testing\RuleTestCase<InnerFunctionRule>
 */
class InnerFunctionRuleTest extends \PHPStan\Testing\RuleTestCase
{
    protected function getRule() : \PHPStan\Rules\Rule
    {
        return new \PHPStan\Rules\Functions\InnerFunctionRule();
    }
    public function testInnerFunction() : void
    {
        $this->analyse([__DIR__ . '/data/inner-functions.php'], [['Inner named functions are not supported by PHPStan. Consider refactoring to an anonymous function, class method, or a top-level-defined function. See issue #165 (https://github.com/phpstan/phpstan/issues/165) for more details.', 7], ['Inner named functions are not supported by PHPStan. Consider refactoring to an anonymous function, class method, or a top-level-defined function. See issue #165 (https://github.com/phpstan/phpstan/issues/165) for more details.', 18]]);
    }
}
