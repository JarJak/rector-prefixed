<?php

declare (strict_types=1);
namespace PHPStan\Rules\Generics;

use PHPStan\Rules\ClassCaseSensitivityCheck;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
/**
 * @extends \PHPStan\Testing\RuleTestCase<FunctionTemplateTypeRule>
 */
class FunctionTemplateTypeRuleTest extends \PHPStan\Testing\RuleTestCase
{
    protected function getRule() : \PHPStan\Rules\Rule
    {
        $broker = $this->createReflectionProvider();
        return new \PHPStan\Rules\Generics\FunctionTemplateTypeRule(self::getContainer()->getByType(\PHPStan\Type\FileTypeMapper::class), new \PHPStan\Rules\Generics\TemplateTypeCheck($broker, new \PHPStan\Rules\ClassCaseSensitivityCheck($broker), ['TypeAlias' => 'int'], \true));
    }
    public function testRule() : void
    {
        $this->analyse([__DIR__ . '/data/function-template.php'], [['PHPDoc tag @template for function FunctionTemplateType\\foo() cannot have existing class stdClass as its name.', 8], ['PHPDoc tag @template T for function FunctionTemplateType\\bar() has invalid bound type FunctionTemplateType\\Zazzzu.', 16], ['PHPDoc tag @template T for function FunctionTemplateType\\baz() with bound type int is not supported.', 24], ['PHPDoc tag @template for function FunctionTemplateType\\lorem() cannot have existing type alias TypeAlias as its name.', 32]]);
    }
}
