<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Reflection\BetterReflection\Reflector;

use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Reflection;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflector\ConstantReflector;
final class MemoizingConstantReflector extends \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflector\ConstantReflector
{
    /** @var array<string, \Roave\BetterReflection\Reflection\ReflectionConstant|\Throwable> */
    private $reflections = [];
    /**
     * Create a ReflectionConstant for the specified $constantName.
     *
     * @return \Roave\BetterReflection\Reflection\ReflectionConstant
     *
     * @throws \Roave\BetterReflection\Reflector\Exception\IdentifierNotFound
     */
    public function reflect(string $constantName) : \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Reflection
    {
        if (isset($this->reflections[$constantName])) {
            if ($this->reflections[$constantName] instanceof \Throwable) {
                throw $this->reflections[$constantName];
            }
            return $this->reflections[$constantName];
        }
        try {
            return $this->reflections[$constantName] = parent::reflect($constantName);
        } catch (\Throwable $e) {
            $this->reflections[$constantName] = $e;
            throw $e;
        }
    }
}
