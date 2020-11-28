<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\StringCast;

use _PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\Exception\MethodPrototypeNotFound;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionClass;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionMethod;
use _PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionParameter;
use function array_reduce;
use function count;
use function sprintf;
/**
 * @internal
 */
final class ReflectionMethodStringCast
{
    public static function toString(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionMethod $methodReflection, ?\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionClass $rootClassReflection = null) : string
    {
        $parametersFormat = $methodReflection->getNumberOfParameters() > 0 ? "\n\n  - Parameters [%d] {%s\n  }" : '';
        return \sprintf('Method [ <%s%s%s%s%s%s>%s%s%s %s method %s ] {%s' . $parametersFormat . "\n}", self::sourceToString($methodReflection), $methodReflection->isConstructor() ? ', ctor' : '', $methodReflection->isDestructor() ? ', dtor' : '', self::overwritesToString($methodReflection), self::inheritsToString($methodReflection, $rootClassReflection), self::prototypeToString($methodReflection), $methodReflection->isFinal() ? ' final' : '', $methodReflection->isStatic() ? ' static' : '', $methodReflection->isAbstract() ? ' abstract' : '', self::visibilityToString($methodReflection), $methodReflection->getName(), self::fileAndLinesToString($methodReflection), \count($methodReflection->getParameters()), self::parametersToString($methodReflection));
    }
    private static function sourceToString(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionMethod $methodReflection) : string
    {
        if ($methodReflection->isUserDefined()) {
            return 'user';
        }
        return \sprintf('internal:%s', $methodReflection->getExtensionName());
    }
    private static function overwritesToString(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionMethod $methodReflection) : string
    {
        $parentClass = $methodReflection->getDeclaringClass()->getParentClass();
        if (!$parentClass) {
            return '';
        }
        if (!$parentClass->hasMethod($methodReflection->getName())) {
            return '';
        }
        return \sprintf(', overwrites %s', $parentClass->getName());
    }
    private static function inheritsToString(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionMethod $methodReflection, ?\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionClass $rootClassReflection) : string
    {
        if (!$rootClassReflection) {
            return '';
        }
        if ($methodReflection->getDeclaringClass()->getName() === $rootClassReflection->getName()) {
            return '';
        }
        return \sprintf(', inherits %s', $methodReflection->getDeclaringClass()->getName());
    }
    private static function prototypeToString(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionMethod $methodReflection) : string
    {
        try {
            return \sprintf(', prototype %s', $methodReflection->getPrototype()->getDeclaringClass()->getName());
        } catch (\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\Exception\MethodPrototypeNotFound $e) {
            return '';
        }
    }
    private static function visibilityToString(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionMethod $methodReflection) : string
    {
        if ($methodReflection->isProtected()) {
            return 'protected';
        }
        if ($methodReflection->isPrivate()) {
            return 'private';
        }
        return 'public';
    }
    private static function fileAndLinesToString(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionMethod $methodReflection) : string
    {
        if ($methodReflection->isInternal()) {
            return '';
        }
        return \sprintf("\n  @@ %s %d - %d", $methodReflection->getFileName(), $methodReflection->getStartLine(), $methodReflection->getEndLine());
    }
    private static function parametersToString(\_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionMethod $methodReflection) : string
    {
        return \array_reduce($methodReflection->getParameters(), static function (string $string, \_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\ReflectionParameter $parameterReflection) : string {
            return $string . "\n    " . \_PhpScoperabd03f0baf05\Roave\BetterReflection\Reflection\StringCast\ReflectionParameterStringCast::toString($parameterReflection);
        }, '');
    }
}
