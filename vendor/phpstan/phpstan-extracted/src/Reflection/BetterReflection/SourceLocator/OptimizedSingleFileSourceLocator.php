<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Reflection\BetterReflection\SourceLocator;

use PhpParser\Node\Expr\FuncCall;
use RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Identifier\Identifier;
use RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Identifier\IdentifierType;
use RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflection\Reflection;
use RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflection\ReflectionClass;
use RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflection\ReflectionConstant;
use RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflection\ReflectionFunction;
use RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflector\Reflector;
use RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\SourceLocator\Ast\Strategy\NodeToReflection;
use RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\SourceLocator\Type\SourceLocator;
class OptimizedSingleFileSourceLocator implements \RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\SourceLocator\Type\SourceLocator
{
    /** @var \PHPStan\Reflection\BetterReflection\SourceLocator\FileNodesFetcher */
    private $fileNodesFetcher;
    /** @var string */
    private $fileName;
    /** @var \PHPStan\Reflection\BetterReflection\SourceLocator\FetchedNodesResult|null */
    private $fetchedNodesResult = null;
    public function __construct(\RectorPrefix20201227\PHPStan\Reflection\BetterReflection\SourceLocator\FileNodesFetcher $fileNodesFetcher, string $fileName)
    {
        $this->fileNodesFetcher = $fileNodesFetcher;
        $this->fileName = $fileName;
    }
    public function locateIdentifier(\RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflector\Reflector $reflector, \RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Identifier\Identifier $identifier) : ?\RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflection\Reflection
    {
        if ($this->fetchedNodesResult === null) {
            $this->fetchedNodesResult = $this->fileNodesFetcher->fetchNodes($this->fileName);
        }
        $nodeToReflection = new \RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\SourceLocator\Ast\Strategy\NodeToReflection();
        if ($identifier->isClass()) {
            $classNodes = $this->fetchedNodesResult->getClassNodes();
            $className = \strtolower($identifier->getName());
            if (!\array_key_exists($className, $classNodes)) {
                return null;
            }
            foreach ($classNodes[$className] as $classNode) {
                $classReflection = $nodeToReflection->__invoke($reflector, $classNode->getNode(), $this->fetchedNodesResult->getLocatedSource(), $classNode->getNamespace());
                if (!$classReflection instanceof \RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflection\ReflectionClass) {
                    throw new \RectorPrefix20201227\PHPStan\ShouldNotHappenException();
                }
                return $classReflection;
            }
        }
        if ($identifier->isFunction()) {
            $functionNodes = $this->fetchedNodesResult->getFunctionNodes();
            $functionName = \strtolower($identifier->getName());
            if (!\array_key_exists($functionName, $functionNodes)) {
                return null;
            }
            $functionReflection = $nodeToReflection->__invoke($reflector, $functionNodes[$functionName]->getNode(), $this->fetchedNodesResult->getLocatedSource(), $functionNodes[$functionName]->getNamespace());
            if (!$functionReflection instanceof \RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflection\ReflectionFunction) {
                throw new \RectorPrefix20201227\PHPStan\ShouldNotHappenException();
            }
            return $functionReflection;
        }
        if ($identifier->isConstant()) {
            $constantNodes = $this->fetchedNodesResult->getConstantNodes();
            foreach ($constantNodes as $stmtConst) {
                if ($stmtConst->getNode() instanceof \PhpParser\Node\Expr\FuncCall) {
                    $constantReflection = $nodeToReflection->__invoke($reflector, $stmtConst->getNode(), $this->fetchedNodesResult->getLocatedSource(), $stmtConst->getNamespace());
                    if ($constantReflection === null) {
                        continue;
                    }
                    if (!$constantReflection instanceof \RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflection\ReflectionConstant) {
                        throw new \RectorPrefix20201227\PHPStan\ShouldNotHappenException();
                    }
                    if ($constantReflection->getName() !== $identifier->getName()) {
                        continue;
                    }
                    return $constantReflection;
                }
                foreach (\array_keys($stmtConst->getNode()->consts) as $i) {
                    $constantReflection = $nodeToReflection->__invoke($reflector, $stmtConst->getNode(), $this->fetchedNodesResult->getLocatedSource(), $stmtConst->getNamespace(), $i);
                    if ($constantReflection === null) {
                        continue;
                    }
                    if (!$constantReflection instanceof \RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflection\ReflectionConstant) {
                        throw new \RectorPrefix20201227\PHPStan\ShouldNotHappenException();
                    }
                    if ($constantReflection->getName() !== $identifier->getName()) {
                        continue;
                    }
                    return $constantReflection;
                }
            }
            return null;
        }
        throw new \RectorPrefix20201227\PHPStan\ShouldNotHappenException();
    }
    public function locateIdentifiersByType(\RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Reflector\Reflector $reflector, \RectorPrefix20201227\_HumbugBox221ad6f1b81f__UniqueRector\Roave\BetterReflection\Identifier\IdentifierType $identifierType) : array
    {
        return [];
    }
}
