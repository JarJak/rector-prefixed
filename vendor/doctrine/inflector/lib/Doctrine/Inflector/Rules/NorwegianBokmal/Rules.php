<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\NorwegianBokmal;

use _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Patterns;
use _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Ruleset;
use _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Substitutions;
use _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformations;
final class Rules
{
    public static function getSingularRuleset() : \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Ruleset
    {
        return new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Ruleset(new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformations(...\_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getSingular()), new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Patterns(...\_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\NorwegianBokmal\Uninflected::getSingular()), (new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Substitutions(...\_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getIrregular()))->getFlippedSubstitutions());
    }
    public static function getPluralRuleset() : \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Ruleset
    {
        return new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Ruleset(new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformations(...\_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getPlural()), new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Patterns(...\_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\NorwegianBokmal\Uninflected::getPlural()), new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Substitutions(...\_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getIrregular()));
    }
}
