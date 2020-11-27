<?php

declare (strict_types=1);
namespace PHPStan\Process\Runnable;

use _PhpScoper006a73f0e455\React\Promise\CancellablePromiseInterface;
interface Runnable
{
    public function getName() : string;
    public function run() : \_PhpScoper006a73f0e455\React\Promise\CancellablePromiseInterface;
    public function cancel() : void;
}