<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Roave\BetterReflection\NodeCompiler;

use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionClass;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\Reflector;
use RuntimeException;
class CompilerContext
{
    /** @var Reflector */
    private $reflector;
    /** @var string|null */
    private $fileName;
    /** @var ReflectionClass|null */
    private $self;
    /** @var string|null */
    private $namespace;
    /** @var string|null */
    private $functionName;
    public function __construct(\_PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\Reflector $reflector, ?string $fileName, ?\_PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionClass $self, ?string $namespace, ?string $functionName)
    {
        $this->reflector = $reflector;
        $this->fileName = $fileName;
        $this->self = $self;
        $this->namespace = $namespace;
        $this->functionName = $functionName;
    }
    /**
     * Does the current context have a "self" or "this"
     *
     * (e.g. if the context is a function, then no, there will be no self)
     */
    public function hasSelf() : bool
    {
        return $this->self !== null;
    }
    public function getSelf() : \_PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionClass
    {
        if (!$this->hasSelf()) {
            throw new \RuntimeException('The current context does not have a class for self');
        }
        return $this->self;
    }
    public function getReflector() : \_PhpScoper006a73f0e455\Roave\BetterReflection\Reflector\Reflector
    {
        return $this->reflector;
    }
    public function hasFileName() : bool
    {
        return $this->fileName !== null;
    }
    public function getFileName() : string
    {
        if (!$this->hasFileName()) {
            throw new \RuntimeException('The current context does not have a filename');
        }
        return $this->fileName;
    }
    public function getNamespace() : ?string
    {
        return $this->namespace;
    }
    public function getFunctionName() : ?string
    {
        return $this->functionName;
    }
}
