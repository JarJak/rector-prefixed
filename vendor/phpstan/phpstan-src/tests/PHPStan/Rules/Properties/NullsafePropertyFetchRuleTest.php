<?php

declare (strict_types=1);
namespace PHPStan\Rules\Properties;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
/**
 * @extends RuleTestCase<NullsafePropertyFetchRule>
 */
class NullsafePropertyFetchRuleTest extends \PHPStan\Testing\RuleTestCase
{
    protected function getRule() : \PHPStan\Rules\Rule
    {
        return new \PHPStan\Rules\Properties\NullsafePropertyFetchRule();
    }
    public function testRule() : void
    {
        if (\PHP_VERSION_ID < 80000 && !self::$useStaticReflectionProvider) {
            $this->markTestSkipped('Test requires PHP 8.0.');
        }
        $this->analyse([__DIR__ . '/data/nullsafe-property-fetch-rule.php'], [['Using nullsafe property access on non-nullable type Exception. Use -> instead.', 16]]);
    }
}
