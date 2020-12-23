<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\DeadCode\UnusedNodeResolver;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Identifier;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Name;
use _PhpScoper0a2ac50786fa\PhpParser\Node\NullableType;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Param;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_;
use _PhpScoper0a2ac50786fa\Rector\Core\Exception\NotImplementedException;
use _PhpScoper0a2ac50786fa\Rector\NodeCollector\NodeCollector\ParsedNodeCollector;
use _PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver;
use _PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
final class UnusedClassResolver
{
    /**
     * @var string[]
     */
    private $cachedUsedClassNames = [];
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var ParsedNodeCollector
     */
    private $parsedNodeCollector;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \_PhpScoper0a2ac50786fa\Rector\NodeCollector\NodeCollector\ParsedNodeCollector $parsedNodeCollector)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->parsedNodeCollector = $parsedNodeCollector;
    }
    public function isClassWithoutInterfaceAndNotController(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class) : bool
    {
        if ($class->implements !== []) {
            return \false;
        }
        if ($class->extends !== null) {
            return \false;
        }
        if ($this->nodeNameResolver->isNames($class, ['*Controller', '*Presenter'])) {
            return \false;
        }
        return !$this->nodeNameResolver->isName($class, '*Test');
    }
    public function isClassUsed(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class) : bool
    {
        return $this->nodeNameResolver->isNames($class, $this->getUsedClassNames());
    }
    /**
     * @return string[]
     */
    private function getUsedClassNames() : array
    {
        if (!\_PhpScoper0a2ac50786fa\Rector\Testing\PHPUnit\StaticPHPUnitEnvironment::isPHPUnitRun() && $this->cachedUsedClassNames !== []) {
            return $this->cachedUsedClassNames;
        }
        $cachedUsedClassNames = \array_merge($this->getParamNodesClassNames(), $this->getNewNodesClassNames(), $this->getStaticCallClassNames(), $this->getClassConstantFetchNames());
        $cachedUsedClassNames = $this->sortAndUniqueArray($cachedUsedClassNames);
        /** @var string[] $cachedUsedClassNames */
        $this->cachedUsedClassNames = $cachedUsedClassNames;
        return $this->cachedUsedClassNames;
    }
    /**
     * @return string[]
     */
    private function getParamNodesClassNames() : array
    {
        $classNames = [];
        foreach ($this->parsedNodeCollector->getParams() as $param) {
            if ($param->type === null) {
                continue;
            }
            if ($param->type instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\NullableType) {
                $param = $param->type;
            }
            if ($param->type instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Identifier) {
                continue;
            }
            if ($param->type instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Name) {
                /** @var string $paramTypeName */
                $paramTypeName = $this->nodeNameResolver->getName($param->type);
                $classNames[] = $paramTypeName;
            } else {
                throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\NotImplementedException();
            }
        }
        return $classNames;
    }
    /**
     * @return string[]
     */
    private function getNewNodesClassNames() : array
    {
        $classNames = [];
        foreach ($this->parsedNodeCollector->getNews() as $newNode) {
            $newClassName = $this->nodeNameResolver->getName($newNode->class);
            if (!\is_string($newClassName)) {
                continue;
            }
            $classNames[] = $newClassName;
        }
        return $classNames;
    }
    /**
     * @return string[]
     */
    private function getStaticCallClassNames() : array
    {
        $classNames = [];
        foreach ($this->parsedNodeCollector->getStaticCalls() as $staticCallNode) {
            $staticClassName = $this->nodeNameResolver->getName($staticCallNode->class);
            if (!\is_string($staticClassName)) {
                continue;
            }
            $classNames[] = $staticClassName;
        }
        return $classNames;
    }
    /**
     * @return string[]
     */
    private function getClassConstantFetchNames() : array
    {
        $classConstFetches = $this->parsedNodeCollector->getClassConstFetches();
        $classNames = [];
        foreach ($classConstFetches as $classConstFetch) {
            $className = $this->nodeNameResolver->getName($classConstFetch->class);
            if (!\is_string($className)) {
                continue;
            }
            $classNames[] = $className;
        }
        return $classNames;
    }
    /**
     * @param string[] $items
     * @return string[]
     */
    private function sortAndUniqueArray(array $items) : array
    {
        \sort($items);
        return \array_unique($items);
    }
}
