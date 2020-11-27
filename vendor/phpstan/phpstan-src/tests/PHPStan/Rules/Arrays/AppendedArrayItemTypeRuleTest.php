<?php

declare (strict_types=1);
namespace PHPStan\Rules\Arrays;

use PHPStan\Rules\Properties\PropertyReflectionFinder;
use PHPStan\Rules\RuleLevelHelper;
/**
 * @extends \PHPStan\Testing\RuleTestCase<AppendedArrayItemTypeRule>
 */
class AppendedArrayItemTypeRuleTest extends \PHPStan\Testing\RuleTestCase
{
    protected function getRule() : \PHPStan\Rules\Rule
    {
        return new \PHPStan\Rules\Arrays\AppendedArrayItemTypeRule(new \PHPStan\Rules\Properties\PropertyReflectionFinder(), new \PHPStan\Rules\RuleLevelHelper($this->createReflectionProvider(), \true, \false, \true, \false));
    }
    public function testAppendedArrayItemType() : void
    {
        $this->analyse([__DIR__ . '/data/appended-array-item.php'], [['Array (array<int>) does not accept string.', 18], ['Array (array<callable(): mixed>) does not accept array(1, 2, 3).', 20], ['Array (array<callable(): mixed>) does not accept array(\'AppendedArrayItem\\\\Foo\', \'classMethod\').', 23], ['Array (array<callable(): mixed>) does not accept array(\'Foo\', \'Hello world\').', 25], ['Array (array<int>) does not accept string.', 27], ['Array (array<int>) does not accept string.', 32], ['Array (array<callable(): string>) does not accept Closure(): int.', 45], ['Array (array<AppendedArrayItem\\Lorem>) does not accept AppendedArrayItem\\Baz.', 79]]);
    }
}
