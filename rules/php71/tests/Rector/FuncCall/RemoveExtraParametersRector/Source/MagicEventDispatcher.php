<?php

declare (strict_types=1);
namespace Rector\Php71\Tests\Rector\FuncCall\RemoveExtraParametersRector\Source;

use _PhpScoper567b66d83109\Symfony\Component\EventDispatcher\Event;
use _PhpScoper567b66d83109\Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
final class MagicEventDispatcher
{
    /**
     * {@inheritdoc}
     *
     * @param string|null $eventName
     */
    public function dispatch($event)
    {
        $eventName = 1 < \func_num_args() ? \func_get_arg(1) : null;
    }
}
