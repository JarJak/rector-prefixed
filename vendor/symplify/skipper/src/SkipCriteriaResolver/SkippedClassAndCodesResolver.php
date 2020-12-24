<?php

declare (strict_types=1);
namespace _PhpScoper0a6b37af0871\Symplify\Skipper\SkipCriteriaResolver;

use _PhpScoper0a6b37af0871\Symplify\PackageBuilder\Parameter\ParameterProvider;
use _PhpScoper0a6b37af0871\Symplify\Skipper\ValueObject\Option;
final class SkippedClassAndCodesResolver
{
    /**
     * @var array<string, string[]|null>
     */
    private $skippedClassAndCodes = [];
    /**
     * @var ParameterProvider
     */
    private $parameterProvider;
    public function __construct(\_PhpScoper0a6b37af0871\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->parameterProvider = $parameterProvider;
    }
    /**
     * @return array<string, string[]|null>
     */
    public function resolve() : array
    {
        if ($this->skippedClassAndCodes !== []) {
            return $this->skippedClassAndCodes;
        }
        $skip = $this->parameterProvider->provideArrayParameter(\_PhpScoper0a6b37af0871\Symplify\Skipper\ValueObject\Option::SKIP);
        foreach ($skip as $key => $value) {
            // e.g. [SomeClass::class] → shift values to [SomeClass::class => null]
            if (\is_int($key)) {
                $key = $value;
                $value = null;
            }
            if (\substr_count($key, '.') !== 1) {
                continue;
            }
            $this->skippedClassAndCodes[$key] = $value;
        }
        return $this->skippedClassAndCodes;
    }
}
