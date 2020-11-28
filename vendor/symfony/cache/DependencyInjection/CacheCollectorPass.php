<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace _PhpScoperabd03f0baf05\Symfony\Component\Cache\DependencyInjection;

use _PhpScoperabd03f0baf05\Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use _PhpScoperabd03f0baf05\Symfony\Component\Cache\Adapter\TraceableAdapter;
use _PhpScoperabd03f0baf05\Symfony\Component\Cache\Adapter\TraceableTagAwareAdapter;
use _PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use _PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\ContainerBuilder;
use _PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\Definition;
use _PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\Reference;
/**
 * Inject a data collector to all the cache services to be able to get detailed statistics.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class CacheCollectorPass implements \_PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    private $dataCollectorCacheId;
    private $cachePoolTag;
    private $cachePoolRecorderInnerSuffix;
    public function __construct(string $dataCollectorCacheId = 'data_collector.cache', string $cachePoolTag = 'cache.pool', string $cachePoolRecorderInnerSuffix = '.recorder_inner')
    {
        $this->dataCollectorCacheId = $dataCollectorCacheId;
        $this->cachePoolTag = $cachePoolTag;
        $this->cachePoolRecorderInnerSuffix = $cachePoolRecorderInnerSuffix;
    }
    /**
     * {@inheritdoc}
     */
    public function process(\_PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->dataCollectorCacheId)) {
            return;
        }
        foreach ($container->findTaggedServiceIds($this->cachePoolTag) as $id => $attributes) {
            $poolName = $attributes[0]['name'] ?? $id;
            $this->addToCollector($id, $poolName, $container);
        }
    }
    private function addToCollector(string $id, string $name, \_PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        $definition = $container->getDefinition($id);
        if ($definition->isAbstract()) {
            return;
        }
        $collectorDefinition = $container->getDefinition($this->dataCollectorCacheId);
        $recorder = new \_PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\Definition(\is_subclass_of($definition->getClass(), \_PhpScoperabd03f0baf05\Symfony\Component\Cache\Adapter\TagAwareAdapterInterface::class) ? \_PhpScoperabd03f0baf05\Symfony\Component\Cache\Adapter\TraceableTagAwareAdapter::class : \_PhpScoperabd03f0baf05\Symfony\Component\Cache\Adapter\TraceableAdapter::class);
        $recorder->setTags($definition->getTags());
        if (!$definition->isPublic() || !$definition->isPrivate()) {
            $recorder->setPublic($definition->isPublic());
        }
        $recorder->setArguments([new \_PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\Reference($innerId = $id . $this->cachePoolRecorderInnerSuffix)]);
        $definition->setTags([]);
        $definition->setPublic(\false);
        $container->setDefinition($innerId, $definition);
        $container->setDefinition($id, $recorder);
        // Tell the collector to add the new instance
        $collectorDefinition->addMethodCall('addInstance', [$name, new \_PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\Reference($id)]);
        $collectorDefinition->setPublic(\false);
    }
}
