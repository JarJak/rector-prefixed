<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\PhpDoc;

use _PhpScoper0a2ac50786fa\PHPStan\Analyser\NameScope;
class NameScopedPhpDocString
{
    /** @var string */
    private $phpDocString;
    /** @var \PHPStan\Analyser\NameScope */
    private $nameScope;
    public function __construct(string $phpDocString, \_PhpScoper0a2ac50786fa\PHPStan\Analyser\NameScope $nameScope)
    {
        $this->phpDocString = $phpDocString;
        $this->nameScope = $nameScope;
    }
    public function getPhpDocString() : string
    {
        return $this->phpDocString;
    }
    public function getNameScope() : \_PhpScoper0a2ac50786fa\PHPStan\Analyser\NameScope
    {
        return $this->nameScope;
    }
    /**
     * @param mixed[] $properties
     * @return self
     */
    public static function __set_state(array $properties) : self
    {
        return new self($properties['phpDocString'], $properties['nameScope']);
    }
}
