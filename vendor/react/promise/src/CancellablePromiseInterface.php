<?php

namespace _PhpScoper006a73f0e455\React\Promise;

interface CancellablePromiseInterface extends \_PhpScoper006a73f0e455\React\Promise\PromiseInterface
{
    /**
     * The `cancel()` method notifies the creator of the promise that there is no
     * further interest in the results of the operation.
     *
     * Once a promise is settled (either fulfilled or rejected), calling `cancel()` on
     * a promise has no effect.
     *
     * @return void
     */
    public function cancel();
}