<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan;

abstract class AnalysedCodeException extends \Exception
{
    public abstract function getTip() : ?string;
}