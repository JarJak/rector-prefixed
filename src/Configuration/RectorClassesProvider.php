<?php

declare (strict_types=1);
namespace Rector\Core\Configuration;

use _PhpScoperabd03f0baf05\Psr\Container\ContainerInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use _PhpScoperabd03f0baf05\Symfony\Component\DependencyInjection\Container;
final class RectorClassesProvider
{
    /**
     * @var ContainerInterface&Container
     */
    private $container;
    /**
     * This is only to prevent circular dependencies, where this service is being used.
     * We only need list of classes.
     *
     * @param ContainerInterface&Container $container
     */
    public function __construct(\_PhpScoperabd03f0baf05\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * @return class-string[]
     */
    public function provide() : array
    {
        $rectorClasses = [];
        foreach ($this->container->getServiceIds() as $class) {
            if (!\class_exists($class)) {
                continue;
            }
            if (!\is_a($class, \Rector\Core\Contract\Rector\RectorInterface::class, \true)) {
                continue;
            }
            $rectorClasses[] = $class;
        }
        \sort($rectorClasses);
        return $rectorClasses;
    }
}
