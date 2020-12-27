<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan;

final class ShouldNotHappenException extends \Exception
{
    public function __construct(string $message = 'Internal error.')
    {
        parent::__construct($message);
    }
}
