<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RectorPrefix20210101\Symfony\Component\Cache;

use RectorPrefix20210101\Psr\Cache\CacheException as Psr6CacheException;
use RectorPrefix20210101\Psr\Cache\CacheItemPoolInterface;
use RectorPrefix20210101\Psr\SimpleCache\CacheException as SimpleCacheException;
use RectorPrefix20210101\Psr\SimpleCache\CacheInterface;
use RectorPrefix20210101\Symfony\Component\Cache\Adapter\AdapterInterface;
use RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException;
use RectorPrefix20210101\Symfony\Component\Cache\Traits\ProxyTrait;
/**
 * Turns a PSR-6 cache into a PSR-16 one.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class Psr16Cache implements \RectorPrefix20210101\Psr\SimpleCache\CacheInterface, \RectorPrefix20210101\Symfony\Component\Cache\PruneableInterface, \RectorPrefix20210101\Symfony\Component\Cache\ResettableInterface
{
    use ProxyTrait;
    private const METADATA_EXPIRY_OFFSET = 1527506807;
    private $createCacheItem;
    private $cacheItemPrototype;
    public function __construct(\RectorPrefix20210101\Psr\Cache\CacheItemPoolInterface $pool)
    {
        $this->pool = $pool;
        if (!$pool instanceof \RectorPrefix20210101\Symfony\Component\Cache\Adapter\AdapterInterface) {
            return;
        }
        $cacheItemPrototype =& $this->cacheItemPrototype;
        $createCacheItem = \Closure::bind(static function ($key, $value, $allowInt = \false) use(&$cacheItemPrototype) {
            $item = clone $cacheItemPrototype;
            $item->poolHash = $item->innerItem = null;
            $item->key = $allowInt && \is_int($key) ? (string) $key : \RectorPrefix20210101\Symfony\Component\Cache\CacheItem::validateKey($key);
            $item->value = $value;
            $item->isHit = \false;
            return $item;
        }, null, \RectorPrefix20210101\Symfony\Component\Cache\CacheItem::class);
        $this->createCacheItem = function ($key, $value, $allowInt = \false) use($createCacheItem) {
            if (null === $this->cacheItemPrototype) {
                $this->get($allowInt && \is_int($key) ? (string) $key : $key);
            }
            $this->createCacheItem = $createCacheItem;
            return $createCacheItem($key, null, $allowInt)->set($value);
        };
    }
    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        try {
            $item = $this->pool->getItem($key);
        } catch (\RectorPrefix20210101\Psr\SimpleCache\CacheException $e) {
            throw $e;
        } catch (\RectorPrefix20210101\Psr\Cache\CacheException $e) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        if (null === $this->cacheItemPrototype) {
            $this->cacheItemPrototype = clone $item;
            $this->cacheItemPrototype->set(null);
        }
        return $item->isHit() ? $item->get() : $default;
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        try {
            if (null !== ($f = $this->createCacheItem)) {
                $item = $f($key, $value);
            } else {
                $item = $this->pool->getItem($key)->set($value);
            }
        } catch (\RectorPrefix20210101\Psr\SimpleCache\CacheException $e) {
            throw $e;
        } catch (\RectorPrefix20210101\Psr\Cache\CacheException $e) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        if (null !== $ttl) {
            $item->expiresAfter($ttl);
        }
        return $this->pool->save($item);
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function delete($key)
    {
        try {
            return $this->pool->deleteItem($key);
        } catch (\RectorPrefix20210101\Psr\SimpleCache\CacheException $e) {
            throw $e;
        } catch (\RectorPrefix20210101\Psr\Cache\CacheException $e) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function clear()
    {
        return $this->pool->clear();
    }
    /**
     * {@inheritdoc}
     *
     * @return iterable
     */
    public function getMultiple($keys, $default = null)
    {
        if ($keys instanceof \Traversable) {
            $keys = \iterator_to_array($keys, \false);
        } elseif (!\is_array($keys)) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException(\sprintf('Cache keys must be array or Traversable, "%s" given.', \get_debug_type($keys)));
        }
        try {
            $items = $this->pool->getItems($keys);
        } catch (\RectorPrefix20210101\Psr\SimpleCache\CacheException $e) {
            throw $e;
        } catch (\RectorPrefix20210101\Psr\Cache\CacheException $e) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        $values = [];
        if (!$this->pool instanceof \RectorPrefix20210101\Symfony\Component\Cache\Adapter\AdapterInterface) {
            foreach ($items as $key => $item) {
                $values[$key] = $item->isHit() ? $item->get() : $default;
            }
            return $values;
        }
        foreach ($items as $key => $item) {
            if (!$item->isHit()) {
                $values[$key] = $default;
                continue;
            }
            $values[$key] = $item->get();
            if (!($metadata = $item->getMetadata())) {
                continue;
            }
            unset($metadata[\RectorPrefix20210101\Symfony\Component\Cache\CacheItem::METADATA_TAGS]);
            if ($metadata) {
                $values[$key] = ["�" . \pack('VN', (int) (0.1 + $metadata[\RectorPrefix20210101\Symfony\Component\Cache\CacheItem::METADATA_EXPIRY] - self::METADATA_EXPIRY_OFFSET), $metadata[\RectorPrefix20210101\Symfony\Component\Cache\CacheItem::METADATA_CTIME]) . "_" => $values[$key]];
            }
        }
        return $values;
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function setMultiple($values, $ttl = null)
    {
        $valuesIsArray = \is_array($values);
        if (!$valuesIsArray && !$values instanceof \Traversable) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException(\sprintf('Cache values must be array or Traversable, "%s" given.', \get_debug_type($values)));
        }
        $items = [];
        try {
            if (null !== ($f = $this->createCacheItem)) {
                $valuesIsArray = \false;
                foreach ($values as $key => $value) {
                    $items[$key] = $f($key, $value, \true);
                }
            } elseif ($valuesIsArray) {
                $items = [];
                foreach ($values as $key => $value) {
                    $items[] = (string) $key;
                }
                $items = $this->pool->getItems($items);
            } else {
                foreach ($values as $key => $value) {
                    if (\is_int($key)) {
                        $key = (string) $key;
                    }
                    $items[$key] = $this->pool->getItem($key)->set($value);
                }
            }
        } catch (\RectorPrefix20210101\Psr\SimpleCache\CacheException $e) {
            throw $e;
        } catch (\RectorPrefix20210101\Psr\Cache\CacheException $e) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
        $ok = \true;
        foreach ($items as $key => $item) {
            if ($valuesIsArray) {
                $item->set($values[$key]);
            }
            if (null !== $ttl) {
                $item->expiresAfter($ttl);
            }
            $ok = $this->pool->saveDeferred($item) && $ok;
        }
        return $this->pool->commit() && $ok;
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function deleteMultiple($keys)
    {
        if ($keys instanceof \Traversable) {
            $keys = \iterator_to_array($keys, \false);
        } elseif (!\is_array($keys)) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException(\sprintf('Cache keys must be array or Traversable, "%s" given.', \get_debug_type($keys)));
        }
        try {
            return $this->pool->deleteItems($keys);
        } catch (\RectorPrefix20210101\Psr\SimpleCache\CacheException $e) {
            throw $e;
        } catch (\RectorPrefix20210101\Psr\Cache\CacheException $e) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function has($key)
    {
        try {
            return $this->pool->hasItem($key);
        } catch (\RectorPrefix20210101\Psr\SimpleCache\CacheException $e) {
            throw $e;
        } catch (\RectorPrefix20210101\Psr\Cache\CacheException $e) {
            throw new \RectorPrefix20210101\Symfony\Component\Cache\Exception\InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
