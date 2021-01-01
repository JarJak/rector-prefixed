<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210101\Symfony\Component\Cache\Exception;

use RectorPrefix20210101\Psr\Cache\InvalidArgumentException as Psr6CacheInterface;
use RectorPrefix20210101\Psr\SimpleCache\InvalidArgumentException as SimpleCacheInterface;
if (\interface_exists(\RectorPrefix20210101\Psr\SimpleCache\InvalidArgumentException::class)) {
    class InvalidArgumentException extends \InvalidArgumentException implements \RectorPrefix20210101\Psr\Cache\InvalidArgumentException, \RectorPrefix20210101\Psr\SimpleCache\InvalidArgumentException
    {
    }
} else {
    class InvalidArgumentException extends \InvalidArgumentException implements \RectorPrefix20210101\Psr\Cache\InvalidArgumentException
    {
    }
}
