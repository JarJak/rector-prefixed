<?php

declare (strict_types=1);
namespace PHPStan\Rules\Exceptions;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
/**
 * @extends \PHPStan\Testing\RuleTestCase<DeadCatchRule>
 */
class DeadCatchRuleTest extends \PHPStan\Testing\RuleTestCase
{
    protected function getRule() : \PHPStan\Rules\Rule
    {
        return new \PHPStan\Rules\Exceptions\DeadCatchRule();
    }
    public function testRule() : void
    {
        $this->analyse([__DIR__ . '/data/dead-catch.php'], [['Dead catch - TypeError is already caught by Throwable above.', 27]]);
    }
}
