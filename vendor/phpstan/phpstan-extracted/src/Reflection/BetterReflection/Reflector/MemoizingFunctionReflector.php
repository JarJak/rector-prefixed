<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Reflection\BetterReflection\Reflector;

use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflector\FunctionReflector;
final class MemoizingFunctionReflector extends \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflector\FunctionReflector
{
    /** @var array<string, \Roave\BetterReflection\Reflection\ReflectionFunction|\Throwable> */
    private $reflections = [];
    /**
     * Create a ReflectionFunction for the specified $functionName.
     *
     * @return \Roave\BetterReflection\Reflection\ReflectionFunction
     *
     * @throws \Roave\BetterReflection\Reflector\Exception\IdentifierNotFound
     */
    public function reflect(string $functionName) : \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Reflection
    {
        $lowerFunctionName = \strtolower($functionName);
        if (isset($this->reflections[$lowerFunctionName])) {
            if ($this->reflections[$lowerFunctionName] instanceof \Throwable) {
                throw $this->reflections[$lowerFunctionName];
            }
            return $this->reflections[$lowerFunctionName];
        }
        try {
            return $this->reflections[$lowerFunctionName] = parent::reflect($functionName);
        } catch (\Throwable $e) {
            $this->reflections[$lowerFunctionName] = $e;
            throw $e;
        }
    }
}
