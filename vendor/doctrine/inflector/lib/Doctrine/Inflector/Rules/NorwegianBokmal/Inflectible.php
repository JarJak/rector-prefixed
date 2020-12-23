<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\NorwegianBokmal;

use _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Pattern;
use _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Substitution;
use _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformation;
use _PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Word;
class Inflectible
{
    /**
     * @return Transformation[]
     */
    public static function getSingular() : iterable
    {
        (yield new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformation(new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Pattern('/re$/i'), 'r'));
        (yield new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformation(new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Pattern('/er$/i'), ''));
    }
    /**
     * @return Transformation[]
     */
    public static function getPlural() : iterable
    {
        (yield new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformation(new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Pattern('/e$/i'), 'er'));
        (yield new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformation(new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Pattern('/r$/i'), 're'));
        (yield new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Transformation(new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Pattern('/$/'), 'er'));
    }
    /**
     * @return Substitution[]
     */
    public static function getIrregular() : iterable
    {
        (yield new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Substitution(new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Word('konto'), new \_PhpScoper0a2ac50786fa\Doctrine\Inflector\Rules\Word('konti')));
    }
}
