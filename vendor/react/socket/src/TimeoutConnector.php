<?php

namespace _PhpScoper26e51eeacccf\React\Socket;

use _PhpScoper26e51eeacccf\React\EventLoop\LoopInterface;
use _PhpScoper26e51eeacccf\React\Promise\Timer;
use _PhpScoper26e51eeacccf\React\Promise\Timer\TimeoutException;
final class TimeoutConnector implements \_PhpScoper26e51eeacccf\React\Socket\ConnectorInterface
{
    private $connector;
    private $timeout;
    private $loop;
    public function __construct(\_PhpScoper26e51eeacccf\React\Socket\ConnectorInterface $connector, $timeout, \_PhpScoper26e51eeacccf\React\EventLoop\LoopInterface $loop)
    {
        $this->connector = $connector;
        $this->timeout = $timeout;
        $this->loop = $loop;
    }
    public function connect($uri)
    {
        return \_PhpScoper26e51eeacccf\React\Promise\Timer\timeout($this->connector->connect($uri), $this->timeout, $this->loop)->then(null, self::handler($uri));
    }
    /**
     * Creates a static rejection handler that reports a proper error message in case of a timeout.
     *
     * This uses a private static helper method to ensure this closure is not
     * bound to this instance and the exception trace does not include a
     * reference to this instance and its connector stack as a result.
     *
     * @param string $uri
     * @return callable
     */
    private static function handler($uri)
    {
        return function (\Exception $e) use($uri) {
            if ($e instanceof \_PhpScoper26e51eeacccf\React\Promise\Timer\TimeoutException) {
                throw new \RuntimeException('Connection to ' . $uri . ' timed out after ' . $e->getTimeout() . ' seconds', \defined('SOCKET_ETIMEDOUT') ? \SOCKET_ETIMEDOUT : 0);
            }
            throw $e;
        };
    }
}
