<?php

declare (strict_types=1);
namespace PHPStan\Rules\Methods;

use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;
/**
 * @extends \PHPStan\Testing\RuleTestCase<CallToMethodStamentWithoutSideEffectsRule>
 */
class CallToMethodStamentWithoutSideEffectsRuleTest extends \PHPStan\Testing\RuleTestCase
{
    protected function getRule() : \PHPStan\Rules\Rule
    {
        return new \PHPStan\Rules\Methods\CallToMethodStamentWithoutSideEffectsRule(new \PHPStan\Rules\RuleLevelHelper($this->createReflectionProvider(), \true, \false, \true, \false));
    }
    public function testRule() : void
    {
        $this->analyse([__DIR__ . '/data/method-call-statement-no-side-effects.php'], [['Call to method DateTimeImmutable::modify() on a separate line has no effect.', 15], ['Call to static method DateTimeImmutable::createFromFormat() on a separate line has no effect.', 16], ['Call to method Exception::getCode() on a separate line has no effect.', 21]]);
    }
    public function testNullsafe() : void
    {
        if (\PHP_VERSION_ID < 80000 && !self::$useStaticReflectionProvider) {
            $this->markTestSkipped('Test requires PHP 8.0.');
        }
        $this->analyse([__DIR__ . '/data/nullsafe-method-call-statement-no-side-effects.php'], [['Call to method Exception::getMessage() on a separate line has no effect.', 10]]);
    }
}
