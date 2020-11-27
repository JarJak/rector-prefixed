<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Doctrine\Inflector\Rules\English;

use _PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Patterns;
use _PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Ruleset;
use _PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Substitutions;
use _PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Transformations;
final class Rules
{
    public static function getSingularRuleset() : \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Ruleset
    {
        return new \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Ruleset(new \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Transformations(...\_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\English\Inflectible::getSingular()), new \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Patterns(...\_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\English\Uninflected::getSingular()), (new \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Substitutions(...\_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\English\Inflectible::getIrregular()))->getFlippedSubstitutions());
    }
    public static function getPluralRuleset() : \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Ruleset
    {
        return new \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Ruleset(new \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Transformations(...\_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\English\Inflectible::getPlural()), new \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Patterns(...\_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\English\Uninflected::getPlural()), new \_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\Substitutions(...\_PhpScoper006a73f0e455\Doctrine\Inflector\Rules\English\Inflectible::getIrregular()));
    }
}
