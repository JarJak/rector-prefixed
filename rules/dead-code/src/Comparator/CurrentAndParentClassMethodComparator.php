<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\DeadCode\Comparator;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Arg;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\StaticCall;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Param;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\MethodReflection;
use _PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use _PhpScoper0a2ac50786fa\Rector\DeadCode\Comparator\Parameter\ParameterDefaultsComparator;
use _PhpScoper0a2ac50786fa\Rector\DeadCode\Comparator\Parameter\ParameterTypeComparator;
use _PhpScoper0a2ac50786fa\Rector\NodeCollector\NodeCollector\NodeRepository;
use _PhpScoper0a2ac50786fa\Rector\NodeCollector\Reflection\MethodReflectionProvider;
use _PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
final class CurrentAndParentClassMethodComparator
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var BetterStandardPrinter
     */
    private $betterStandardPrinter;
    /**
     * @var NodeRepository
     */
    private $nodeRepository;
    /**
     * @var MethodReflectionProvider
     */
    private $methodReflectionProvider;
    /**
     * @var ParameterDefaultsComparator
     */
    private $parameterDefaultsComparator;
    /**
     * @var ParameterTypeComparator
     */
    private $parameterTypeComparator;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Printer\BetterStandardPrinter $betterStandardPrinter, \_PhpScoper0a2ac50786fa\Rector\NodeCollector\NodeCollector\NodeRepository $nodeRepository, \_PhpScoper0a2ac50786fa\Rector\NodeCollector\Reflection\MethodReflectionProvider $methodReflectionProvider, \_PhpScoper0a2ac50786fa\Rector\DeadCode\Comparator\Parameter\ParameterDefaultsComparator $parameterDefaultsComparator, \_PhpScoper0a2ac50786fa\Rector\DeadCode\Comparator\Parameter\ParameterTypeComparator $parameterTypeComparator)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->nodeRepository = $nodeRepository;
        $this->methodReflectionProvider = $methodReflectionProvider;
        $this->parameterDefaultsComparator = $parameterDefaultsComparator;
        $this->parameterTypeComparator = $parameterTypeComparator;
    }
    public function isParentCallMatching(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\StaticCall $staticCall) : bool
    {
        if (!$this->isSameMethodParentCall($classMethod, $staticCall)) {
            return \false;
        }
        if (!$this->areArgsAndParamsEqual($staticCall->args, $classMethod->params)) {
            return \false;
        }
        if (!$this->parameterTypeComparator->compareCurrentClassMethodAndParentStaticCall($classMethod, $staticCall)) {
            return \false;
        }
        return !$this->isParentClassMethodVisibilityOrDefaultOverride($classMethod, $staticCall);
    }
    private function isSameMethodParentCall(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\StaticCall $staticCall) : bool
    {
        if (!$this->nodeNameResolver->areNamesEqual($staticCall->name, $classMethod->name)) {
            return \false;
        }
        return $this->nodeNameResolver->isName($staticCall->class, 'parent');
    }
    /**
     * @param Arg[] $parentStaticCallArgs
     * @param Param[] $currentClassMethodParams
     */
    private function areArgsAndParamsEqual(array $parentStaticCallArgs, array $currentClassMethodParams) : bool
    {
        if (\count($parentStaticCallArgs) !== \count($currentClassMethodParams)) {
            return \false;
        }
        if ($parentStaticCallArgs === []) {
            return \true;
        }
        foreach ($parentStaticCallArgs as $key => $arg) {
            if (!isset($currentClassMethodParams[$key])) {
                return \false;
            }
            // this only compares variable name, but those can be differnt, so its kinda useless
            $param = $currentClassMethodParams[$key];
            if (!$this->betterStandardPrinter->areNodesEqual($param->var, $arg->value)) {
                return \false;
            }
        }
        return \true;
    }
    private function isParentClassMethodVisibilityOrDefaultOverride(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\StaticCall $staticCall) : bool
    {
        /** @var string $className */
        $className = $staticCall->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        $parentClassName = \get_parent_class($className);
        if (!$parentClassName) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        /** @var string $methodName */
        $methodName = $this->nodeNameResolver->getName($staticCall->name);
        $parentClassMethod = $this->nodeRepository->findClassMethod($parentClassName, $methodName);
        if ($parentClassMethod !== null && $parentClassMethod->isProtected() && $classMethod->isPublic()) {
            return \true;
        }
        return $this->checkOverrideUsingReflection($classMethod, $parentClassName, $methodName);
    }
    private function checkOverrideUsingReflection(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod, string $parentClassName, string $methodName) : bool
    {
        // @todo use phpstan reflecoitn
        $scope = $classMethod->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::SCOPE);
        if (!$scope instanceof \_PhpScoper0a2ac50786fa\PHPStan\Analyser\Scope) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        $parentMethodReflection = $this->methodReflectionProvider->provideByClassAndMethodName($parentClassName, $methodName, $scope);
        // 3rd party code
        if ($parentMethodReflection !== null) {
            if (!$parentMethodReflection->isPrivate() && !$parentMethodReflection->isPublic() && $classMethod->isPublic()) {
                return \true;
            }
            if ($parentMethodReflection->isInternal()->yes()) {
                // we can't know for certain so we assume its a override with purpose
                return \true;
            }
            if ($this->areParameterDefaultsDifferent($classMethod, $parentMethodReflection)) {
                return \true;
            }
        }
        return \false;
    }
    private function areParameterDefaultsDifferent(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod, \_PhpScoper0a2ac50786fa\PHPStan\Reflection\MethodReflection $methodReflection) : bool
    {
        $parameterReflections = $this->methodReflectionProvider->getParameterReflectionsFromMethodReflection($methodReflection);
        foreach ($parameterReflections as $key => $parameterReflection) {
            if (!isset($classMethod->params[$key])) {
                if ($parameterReflection->getDefaultValue() !== null) {
                    continue;
                }
                return \true;
            }
            $methodParam = $classMethod->params[$key];
            if ($this->parameterDefaultsComparator->areDefaultValuesDifferent($parameterReflection, $methodParam)) {
                return \true;
            }
        }
        return \false;
    }
}
