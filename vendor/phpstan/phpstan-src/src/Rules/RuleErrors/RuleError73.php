<?php

declare (strict_types=1);
namespace PHPStan\Rules\RuleErrors;

/**
 * @internal Use PHPStan\Rules\RuleErrorBuilder instead.
 */
class RuleError73 implements \PHPStan\Rules\RuleError, \PHPStan\Rules\TipRuleError, \PHPStan\Rules\NonIgnorableRuleError
{
    /**
     * @var string
     */
    public $message;
    /**
     * @var string
     */
    public $tip;
    public function getMessage() : string
    {
        return $this->message;
    }
    public function getTip() : string
    {
        return $this->tip;
    }
}
