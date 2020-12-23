<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrors;

/**
 * @internal Use PHPStan\Rules\RuleErrorBuilder instead.
 */
class RuleError13 implements \_PhpScoper0a2ac50786fa\PHPStan\Rules\RuleError, \_PhpScoper0a2ac50786fa\PHPStan\Rules\FileRuleError, \_PhpScoper0a2ac50786fa\PHPStan\Rules\TipRuleError
{
    /** @var string */
    public $message;
    /** @var string */
    public $file;
    /** @var string */
    public $tip;
    public function getMessage() : string
    {
        return $this->message;
    }
    public function getFile() : string
    {
        return $this->file;
    }
    public function getTip() : string
    {
        return $this->tip;
    }
}
