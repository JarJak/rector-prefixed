<?php

declare (strict_types=1);
namespace _PhpScoper567b66d83109\Doctrine\Inflector;

use _PhpScoper567b66d83109\Doctrine\Inflector\Rules\English;
use _PhpScoper567b66d83109\Doctrine\Inflector\Rules\French;
use _PhpScoper567b66d83109\Doctrine\Inflector\Rules\NorwegianBokmal;
use _PhpScoper567b66d83109\Doctrine\Inflector\Rules\Portuguese;
use _PhpScoper567b66d83109\Doctrine\Inflector\Rules\Spanish;
use _PhpScoper567b66d83109\Doctrine\Inflector\Rules\Turkish;
use InvalidArgumentException;
use function sprintf;
final class InflectorFactory
{
    public static function create() : \_PhpScoper567b66d83109\Doctrine\Inflector\LanguageInflectorFactory
    {
        return self::createForLanguage(\_PhpScoper567b66d83109\Doctrine\Inflector\Language::ENGLISH);
    }
    public static function createForLanguage(string $language) : \_PhpScoper567b66d83109\Doctrine\Inflector\LanguageInflectorFactory
    {
        switch ($language) {
            case \_PhpScoper567b66d83109\Doctrine\Inflector\Language::ENGLISH:
                return new \_PhpScoper567b66d83109\Doctrine\Inflector\Rules\English\InflectorFactory();
            case \_PhpScoper567b66d83109\Doctrine\Inflector\Language::FRENCH:
                return new \_PhpScoper567b66d83109\Doctrine\Inflector\Rules\French\InflectorFactory();
            case \_PhpScoper567b66d83109\Doctrine\Inflector\Language::NORWEGIAN_BOKMAL:
                return new \_PhpScoper567b66d83109\Doctrine\Inflector\Rules\NorwegianBokmal\InflectorFactory();
            case \_PhpScoper567b66d83109\Doctrine\Inflector\Language::PORTUGUESE:
                return new \_PhpScoper567b66d83109\Doctrine\Inflector\Rules\Portuguese\InflectorFactory();
            case \_PhpScoper567b66d83109\Doctrine\Inflector\Language::SPANISH:
                return new \_PhpScoper567b66d83109\Doctrine\Inflector\Rules\Spanish\InflectorFactory();
            case \_PhpScoper567b66d83109\Doctrine\Inflector\Language::TURKISH:
                return new \_PhpScoper567b66d83109\Doctrine\Inflector\Rules\Turkish\InflectorFactory();
            default:
                throw new \InvalidArgumentException(\sprintf('Language "%s" is not supported.', $language));
        }
    }
}
