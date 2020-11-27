<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Nette\DI\Config;

use _PhpScoper006a73f0e455\Nette;
/**
 * Configuration helpers.
 * @deprecated
 */
final class Helpers
{
    use Nette\StaticClass;
    public const PREVENT_MERGING = '_prevent_merging';
    /**
     * Merges configurations. Left has higher priority than right one.
     * @return array|string
     */
    public static function merge($left, $right)
    {
        return \_PhpScoper006a73f0e455\Nette\Schema\Helpers::merge($left, $right);
    }
    /**
     * Return true if array prevents merging and removes this information.
     */
    public static function takeParent(&$data) : bool
    {
        if (\is_array($data) && isset($data[self::PREVENT_MERGING])) {
            unset($data[self::PREVENT_MERGING]);
            return \true;
        }
        return \false;
    }
}