<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Core\ValueObject;

final class StaticNonPhpFileSuffixes
{
    /**
     * @var string[]
     */
    public const SUFFIXES = ['neon', 'yaml', 'xml', 'yml', 'twig', 'latte', 'blade.php'];
    public static function getSuffixRegexPattern() : string
    {
        $quotedSuffixes = [];
        foreach (self::SUFFIXES as $suffix) {
            $quotedSuffixes[] = \preg_quote($suffix, '#');
        }
        return '#\\.(' . \implode('|', $quotedSuffixes) . ')$#i';
    }
}
