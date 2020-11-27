<?php

declare (strict_types=1);
namespace PHPStan\Rules\Functions;

use PHPStan\Rules\NullsafeCheck;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
/**
 * @extends RuleTestCase<ArrowFunctionReturnNullsafeByRefRule>
 */
class ArrowFunctionReturnNullsafeByRefRuleTest extends \PHPStan\Testing\RuleTestCase
{
    protected function getRule() : \PHPStan\Rules\Rule
    {
        return new \PHPStan\Rules\Functions\ArrowFunctionReturnNullsafeByRefRule(new \PHPStan\Rules\NullsafeCheck());
    }
    public function testRule() : void
    {
        if (!self::$useStaticReflectionProvider) {
            $this->markTestSkipped('Test requires static reflection.');
        }
        $this->analyse([__DIR__ . '/data/arrow-function-nullsafe-by-ref.php'], [['Nullsafe cannot be returned by reference.', 6]]);
    }
}
