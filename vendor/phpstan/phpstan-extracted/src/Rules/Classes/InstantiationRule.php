<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Rules\Classes;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use RectorPrefix20201227\PHPStan\Analyser\Scope;
use RectorPrefix20201227\PHPStan\Reflection\ParametersAcceptorSelector;
use RectorPrefix20201227\PHPStan\Reflection\Php\PhpMethodReflection;
use RectorPrefix20201227\PHPStan\Reflection\ReflectionProvider;
use RectorPrefix20201227\PHPStan\Rules\ClassCaseSensitivityCheck;
use RectorPrefix20201227\PHPStan\Rules\ClassNameNodePair;
use RectorPrefix20201227\PHPStan\Rules\FunctionCallParametersCheck;
use RectorPrefix20201227\PHPStan\Rules\RuleError;
use RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\TypeUtils;
use PHPStan\Type\TypeWithClassName;
/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\New_>
 */
class InstantiationRule implements \RectorPrefix20201227\PHPStan\Rules\Rule
{
    /** @var \PHPStan\Reflection\ReflectionProvider */
    private $reflectionProvider;
    /** @var \PHPStan\Rules\FunctionCallParametersCheck */
    private $check;
    /** @var \PHPStan\Rules\ClassCaseSensitivityCheck */
    private $classCaseSensitivityCheck;
    public function __construct(\RectorPrefix20201227\PHPStan\Reflection\ReflectionProvider $reflectionProvider, \RectorPrefix20201227\PHPStan\Rules\FunctionCallParametersCheck $check, \RectorPrefix20201227\PHPStan\Rules\ClassCaseSensitivityCheck $classCaseSensitivityCheck)
    {
        $this->reflectionProvider = $reflectionProvider;
        $this->check = $check;
        $this->classCaseSensitivityCheck = $classCaseSensitivityCheck;
    }
    public function getNodeType() : string
    {
        return \PhpParser\Node\Expr\New_::class;
    }
    public function processNode(\PhpParser\Node $node, \RectorPrefix20201227\PHPStan\Analyser\Scope $scope) : array
    {
        $errors = [];
        foreach ($this->getClassNames($node, $scope) as $class) {
            $errors = \array_merge($errors, $this->checkClassName($class, $node, $scope));
        }
        return $errors;
    }
    /**
     * @param string $class
     * @param \PhpParser\Node\Expr\New_ $node
     * @param Scope $scope
     * @return RuleError[]
     */
    private function checkClassName(string $class, \PhpParser\Node $node, \RectorPrefix20201227\PHPStan\Analyser\Scope $scope) : array
    {
        $lowercasedClass = \strtolower($class);
        $messages = [];
        $isStatic = \false;
        if ($lowercasedClass === 'static') {
            if (!$scope->isInClass()) {
                return [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Using %s outside of class scope.', $class))->build()];
            }
            $isStatic = \true;
            $classReflection = $scope->getClassReflection();
            if (!$classReflection->isFinal()) {
                if (!$classReflection->hasConstructor()) {
                    return [];
                }
                $constructor = $classReflection->getConstructor();
                if (!$constructor->getPrototype()->getDeclaringClass()->isInterface() && $constructor instanceof \RectorPrefix20201227\PHPStan\Reflection\Php\PhpMethodReflection && !$constructor->isFinal()->yes() && !$constructor->getPrototype()->isAbstract()) {
                    return [];
                }
            }
        } elseif ($lowercasedClass === 'self') {
            if (!$scope->isInClass()) {
                return [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Using %s outside of class scope.', $class))->build()];
            }
            $classReflection = $scope->getClassReflection();
        } elseif ($lowercasedClass === 'parent') {
            if (!$scope->isInClass()) {
                return [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Using %s outside of class scope.', $class))->build()];
            }
            if ($scope->getClassReflection()->getParentClass() === \false) {
                return [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('%s::%s() calls new parent but %s does not extend any class.', $scope->getClassReflection()->getDisplayName(), $scope->getFunctionName(), $scope->getClassReflection()->getDisplayName()))->build()];
            }
            $classReflection = $scope->getClassReflection()->getParentClass();
        } else {
            if (!$this->reflectionProvider->hasClass($class)) {
                if ($scope->isInClassExists($class)) {
                    return [];
                }
                return [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Instantiated class %s not found.', $class))->discoveringSymbolsTip()->build()];
            } else {
                $messages = $this->classCaseSensitivityCheck->checkClassNames([new \RectorPrefix20201227\PHPStan\Rules\ClassNameNodePair($class, $node->class)]);
            }
            $classReflection = $this->reflectionProvider->getClass($class);
        }
        if (!$isStatic && $classReflection->isInterface()) {
            return [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Cannot instantiate interface %s.', $classReflection->getDisplayName()))->build()];
        }
        if (!$isStatic && $classReflection->isAbstract()) {
            return [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Instantiated class %s is abstract.', $classReflection->getDisplayName()))->build()];
        }
        if (!$classReflection->hasConstructor()) {
            if (\count($node->args) > 0) {
                return \array_merge($messages, [\RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Class %s does not have a constructor and must be instantiated without any parameters.', $classReflection->getDisplayName()))->build()]);
            }
            return $messages;
        }
        $constructorReflection = $classReflection->getConstructor();
        if (!$scope->canCallMethod($constructorReflection)) {
            $messages[] = \RectorPrefix20201227\PHPStan\Rules\RuleErrorBuilder::message(\sprintf('Cannot instantiate class %s via %s constructor %s::%s().', $classReflection->getDisplayName(), $constructorReflection->isPrivate() ? 'private' : 'protected', $constructorReflection->getDeclaringClass()->getDisplayName(), $constructorReflection->getName()))->build();
        }
        return \array_merge($messages, $this->check->check(\RectorPrefix20201227\PHPStan\Reflection\ParametersAcceptorSelector::selectFromArgs($scope, $node->args, $constructorReflection->getVariants()), $scope, $constructorReflection->getDeclaringClass()->isBuiltin(), $node, [
            'Class ' . $classReflection->getDisplayName() . ' constructor invoked with %d parameter, %d required.',
            'Class ' . $classReflection->getDisplayName() . ' constructor invoked with %d parameters, %d required.',
            'Class ' . $classReflection->getDisplayName() . ' constructor invoked with %d parameter, at least %d required.',
            'Class ' . $classReflection->getDisplayName() . ' constructor invoked with %d parameters, at least %d required.',
            'Class ' . $classReflection->getDisplayName() . ' constructor invoked with %d parameter, %d-%d required.',
            'Class ' . $classReflection->getDisplayName() . ' constructor invoked with %d parameters, %d-%d required.',
            'Parameter %s of class ' . $classReflection->getDisplayName() . ' constructor expects %s, %s given.',
            '',
            // constructor does not have a return type
            'Parameter %s of class ' . $classReflection->getDisplayName() . ' constructor is passed by reference, so it expects variables only',
            'Unable to resolve the template type %s in instantiation of class ' . $classReflection->getDisplayName(),
            'Missing parameter $%s in call to ' . $classReflection->getDisplayName() . ' constructor.',
            'Unknown parameter $%s in call to ' . $classReflection->getDisplayName() . ' constructor.',
        ]));
    }
    /**
     * @param \PhpParser\Node\Expr\New_ $node $node
     * @param Scope $scope
     * @return string[]
     */
    private function getClassNames(\PhpParser\Node $node, \RectorPrefix20201227\PHPStan\Analyser\Scope $scope) : array
    {
        if ($node->class instanceof \PhpParser\Node\Name) {
            return [(string) $node->class];
        }
        if ($node->class instanceof \PhpParser\Node\Stmt\Class_) {
            $anonymousClassType = $scope->getType($node);
            if (!$anonymousClassType instanceof \PHPStan\Type\TypeWithClassName) {
                throw new \RectorPrefix20201227\PHPStan\ShouldNotHappenException();
            }
            return [$anonymousClassType->getClassName()];
        }
        $type = $scope->getType($node->class);
        return \array_merge(\array_map(static function (\PHPStan\Type\Constant\ConstantStringType $type) : string {
            return $type->getValue();
        }, \PHPStan\Type\TypeUtils::getConstantStrings($type)), \PHPStan\Type\TypeUtils::getDirectClassNames($type));
    }
}
