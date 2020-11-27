<?php

declare (strict_types=1);
namespace PHPStan\Rules\DeadCode;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
/**
 * @extends \PHPStan\Testing\RuleTestCase<UnreachableStatementRule>
 */
class UnreachableStatementRuleTest extends \PHPStan\Testing\RuleTestCase
{
    /** @var bool */
    private $treatPhpDocTypesAsCertain;
    protected function getRule() : \PHPStan\Rules\Rule
    {
        return new \PHPStan\Rules\DeadCode\UnreachableStatementRule();
    }
    protected function shouldTreatPhpDocTypesAsCertain() : bool
    {
        return $this->treatPhpDocTypesAsCertain;
    }
    public function testRule() : void
    {
        $this->treatPhpDocTypesAsCertain = \true;
        $this->analyse([__DIR__ . '/data/unreachable.php'], [['Unreachable statement - code above always terminates.', 12], ['Unreachable statement - code above always terminates.', 19], ['Unreachable statement - code above always terminates.', 30], ['Unreachable statement - code above always terminates.', 71]]);
    }
    public function testRuleTopLevel() : void
    {
        $this->treatPhpDocTypesAsCertain = \true;
        $this->analyse([__DIR__ . '/data/unreachable-top-level.php'], [['Unreachable statement - code above always terminates.', 5]]);
    }
    public function dataBugWithoutGitHubIssue1() : array
    {
        return [[\true], [\false]];
    }
    /**
     * @dataProvider dataBugWithoutGitHubIssue1
     * @param bool $treatPhpDocTypesAsCertain
     */
    public function testBugWithoutGitHubIssue1(bool $treatPhpDocTypesAsCertain) : void
    {
        $this->treatPhpDocTypesAsCertain = $treatPhpDocTypesAsCertain;
        $this->analyse([__DIR__ . '/data/bug-without-issue-1.php'], []);
    }
    public function testBug4070() : void
    {
        $this->treatPhpDocTypesAsCertain = \true;
        $this->analyse([__DIR__ . '/data/bug-4070.php'], []);
    }
    public function testBug4070Two() : void
    {
        $this->treatPhpDocTypesAsCertain = \true;
        $this->analyse([__DIR__ . '/data/bug-4070_2.php'], []);
    }
    public function testBug4076() : void
    {
        $this->treatPhpDocTypesAsCertain = \true;
        $this->analyse([__DIR__ . '/data/bug-4076.php'], []);
    }
}