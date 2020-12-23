<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules;

class Ruleset
{
    /** @var Transformations */
    private $regular;
    /** @var Patterns */
    private $uninflected;
    /** @var Substitutions */
    private $irregular;
    public function __construct(\_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformations $regular, \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Patterns $uninflected, \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Substitutions $irregular)
    {
        $this->regular = $regular;
        $this->uninflected = $uninflected;
        $this->irregular = $irregular;
    }
    public function getRegular() : \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformations
    {
        return $this->regular;
    }
    public function getUninflected() : \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Patterns
    {
        return $this->uninflected;
    }
    public function getIrregular() : \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Substitutions
    {
        return $this->irregular;
    }
}
