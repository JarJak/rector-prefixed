<?php

declare (strict_types=1);
namespace PHPStan\Rules\Classes;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\New_>
 */
class NewStaticRule implements \PHPStan\Rules\Rule
{
    public function getNodeType() : string
    {
        return \PhpParser\Node\Expr\New_::class;
    }
    public function processNode(\PhpParser\Node $node, \PHPStan\Analyser\Scope $scope) : array
    {
        if (!$node->class instanceof \PhpParser\Node\Name) {
            return [];
        }
        if (!$scope->isInClass()) {
            return [];
        }
        if (\strtolower($node->class->toString()) !== 'static') {
            return [];
        }
        $classReflection = $scope->getClassReflection();
        if ($classReflection->isFinal()) {
            return [];
        }
        $messages = [\PHPStan\Rules\RuleErrorBuilder::message('Unsafe usage of new static().')->tip('Consider making the class or the constructor final.')->build()];
        if (!$classReflection->hasConstructor()) {
            return $messages;
        }
        $constructor = $classReflection->getConstructor();
        if ($constructor->getPrototype()->getDeclaringClass()->isInterface()) {
            return [];
        }
        if ($constructor instanceof \PHPStan\Reflection\Php\PhpMethodReflection) {
            if ($constructor->isFinal()->yes()) {
                return [];
            }
            $prototype = $constructor->getPrototype();
            if ($prototype->isAbstract()) {
                return [];
            }
        }
        return $messages;
    }
}
