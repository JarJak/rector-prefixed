<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Rules\RuleErrors;

/**
 * @internal Use PHPStan\Rules\RuleErrorBuilder instead.
 */
class RuleError25 implements \RectorPrefix20201227\PHPStan\Rules\RuleError, \RectorPrefix20201227\PHPStan\Rules\TipRuleError, \RectorPrefix20201227\PHPStan\Rules\IdentifierRuleError
{
    /** @var string */
    public $message;
    /** @var string */
    public $tip;
    /** @var string */
    public $identifier;
    public function getMessage() : string
    {
        return $this->message;
    }
    public function getTip() : string
    {
        return $this->tip;
    }
    public function getIdentifier() : string
    {
        return $this->identifier;
    }
}
