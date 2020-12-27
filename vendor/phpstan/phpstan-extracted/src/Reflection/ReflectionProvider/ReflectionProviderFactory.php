<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Reflection\ReflectionProvider;

use RectorPrefix20201227\PHPStan\Reflection\ReflectionProvider;
class ReflectionProviderFactory
{
    /** @var \PHPStan\Reflection\ReflectionProvider */
    private $runtimeReflectionProvider;
    /** @var \PHPStan\Reflection\ReflectionProvider */
    private $staticReflectionProvider;
    /** @var bool */
    private $disableRuntimeReflectionProvider;
    public function __construct(\RectorPrefix20201227\PHPStan\Reflection\ReflectionProvider $runtimeReflectionProvider, \RectorPrefix20201227\PHPStan\Reflection\ReflectionProvider $staticReflectionProvider, bool $disableRuntimeReflectionProvider)
    {
        $this->runtimeReflectionProvider = $runtimeReflectionProvider;
        $this->staticReflectionProvider = $staticReflectionProvider;
        $this->disableRuntimeReflectionProvider = $disableRuntimeReflectionProvider;
    }
    public function create() : \RectorPrefix20201227\PHPStan\Reflection\ReflectionProvider
    {
        $providers = [];
        if (!$this->disableRuntimeReflectionProvider) {
            $providers[] = $this->runtimeReflectionProvider;
        }
        $providers[] = $this->staticReflectionProvider;
        return new \RectorPrefix20201227\PHPStan\Reflection\ReflectionProvider\MemoizingReflectionProvider(\count($providers) === 1 ? $providers[0] : new \RectorPrefix20201227\PHPStan\Reflection\ReflectionProvider\ChainReflectionProvider($providers));
    }
}
