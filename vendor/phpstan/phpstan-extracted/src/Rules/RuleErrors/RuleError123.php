<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Rules\RuleErrors;

/**
 * @internal Use PHPStan\Rules\RuleErrorBuilder instead.
 */
class RuleError123 implements \RectorPrefix20201227\PHPStan\Rules\RuleError, \RectorPrefix20201227\PHPStan\Rules\LineRuleError, \RectorPrefix20201227\PHPStan\Rules\TipRuleError, \RectorPrefix20201227\PHPStan\Rules\IdentifierRuleError, \RectorPrefix20201227\PHPStan\Rules\MetadataRuleError, \RectorPrefix20201227\PHPStan\Rules\NonIgnorableRuleError
{
    /** @var string */
    public $message;
    /** @var int */
    public $line;
    /** @var string */
    public $tip;
    /** @var string */
    public $identifier;
    /** @var mixed[] */
    public $metadata;
    public function getMessage() : string
    {
        return $this->message;
    }
    public function getLine() : int
    {
        return $this->line;
    }
    public function getTip() : string
    {
        return $this->tip;
    }
    public function getIdentifier() : string
    {
        return $this->identifier;
    }
    /**
     * @return mixed[]
     */
    public function getMetadata() : array
    {
        return $this->metadata;
    }
}
