<?php

declare (strict_types=1);
namespace PHPStan\Rules\Exceptions;

use PHPStan\Php\PhpVersion;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
/**
 * @extends RuleTestCase<ThrowExpressionRule>
 */
class ThrowExpressionRuleTest extends \PHPStan\Testing\RuleTestCase
{
    /** @var PhpVersion */
    private $phpVersion;
    protected function getRule() : \PHPStan\Rules\Rule
    {
        return new \PHPStan\Rules\Exceptions\ThrowExpressionRule($this->phpVersion);
    }
    public function dataRule() : array
    {
        return [[70400, [['Throw expression is supported only on PHP 8.0 and later.', 10]]], [80000, []]];
    }
    /**
     * @dataProvider dataRule
     * @param int $phpVersion
     * @param mixed[] $expectedErrors
     */
    public function testRule(int $phpVersion, array $expectedErrors) : void
    {
        if (\PHP_VERSION_ID < 80000 && !self::$useStaticReflectionProvider) {
            $this->markTestSkipped('Test requires PHP 8.0');
        }
        $this->phpVersion = new \PHPStan\Php\PhpVersion($phpVersion);
        $this->analyse([__DIR__ . '/data/throw-expr.php'], $expectedErrors);
    }
}
