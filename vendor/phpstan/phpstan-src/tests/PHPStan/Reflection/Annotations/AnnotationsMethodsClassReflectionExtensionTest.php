<?php

declare (strict_types=1);
namespace PHPStan\Reflection\Annotations;

use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\PassedByReference;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Type\VerbosityLevel;
class AnnotationsMethodsClassReflectionExtensionTest extends \PHPStan\Testing\TestCase
{
    public function dataMethods() : array
    {
        $fooMethods = ['getInteger' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'int', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomething' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'getFooOrBar' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\AnnotationsMethods\\Foo', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'methodWithNoReturnType' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'mixed', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getIntegerStatically' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'int', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomethingStatically' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'void', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'getFooOrBarStatically' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\AnnotationsMethods\\Foo', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'methodWithNoReturnTypeStatically' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'static(AnnotationsMethods\\Foo)', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getIntegerWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'int', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomethingWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'getFooOrBarWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\AnnotationsMethods\\Foo', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'methodWithNoReturnTypeWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'mixed', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getIntegerStaticallyWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'int', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomethingStaticallyWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'void', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'getFooOrBarStaticallyWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\AnnotationsMethods\\Foo', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'methodWithNoReturnTypeStaticallyWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'static(AnnotationsMethods\\Foo)', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'aStaticMethodThatHasAUniqueReturnTypeInThisClass' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'bool', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'aStaticMethodThatHasAUniqueReturnTypeInThisClassWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'string', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'getIntegerNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'int', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'doSomethingNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getFooOrBarNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\AnnotationsMethods\\Foo', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'methodWithNoReturnTypeNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'mixed', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getIntegerStaticallyNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'int', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'doSomethingStaticallyNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'void', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'getFooOrBarStaticallyNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\AnnotationsMethods\\Foo', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'methodWithNoReturnTypeStaticallyNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'static(AnnotationsMethods\\Foo)', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getIntegerWithDescriptionNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'int', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'doSomethingWithDescriptionNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getFooOrBarWithDescriptionNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\AnnotationsMethods\\Foo', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getIntegerStaticallyWithDescriptionNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'int', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'doSomethingStaticallyWithDescriptionNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'void', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'getFooOrBarStaticallyWithDescriptionNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\AnnotationsMethods\\Foo', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'aStaticMethodThatHasAUniqueReturnTypeInThisClassNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'bool|string', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'aStaticMethodThatHasAUniqueReturnTypeInThisClassWithDescriptionNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => 'float|string', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'methodFromInterface' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\FooInterface::class, 'returnType' => \_PhpScopera143bcca66cb\AnnotationsMethods\FooInterface::class, 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'publish' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\Aws\\Result', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'args', 'type' => 'array', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \false]]], 'rotate' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => '_PhpScopera143bcca66cb\\AnnotationsMethods\\Image', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'angle', 'type' => 'float', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'backgroundColor', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'overridenMethod' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'overridenMethodWithAnnotation' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'returnType' => \_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []]];
        $barMethods = \array_merge($fooMethods, ['overridenMethod' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Bar::class, 'returnType' => \_PhpScopera143bcca66cb\AnnotationsMethods\Bar::class, 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'overridenMethodWithAnnotation' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Bar::class, 'returnType' => \_PhpScopera143bcca66cb\AnnotationsMethods\Bar::class, 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'conflictingMethod' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Bar::class, 'returnType' => \_PhpScopera143bcca66cb\AnnotationsMethods\Bar::class, 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []]]);
        $bazMethods = \array_merge($barMethods, ['doSomething' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'getIpsum' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => '_PhpScopera143bcca66cb\\OtherNamespace\\Ipsum', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'getIpsumStatically' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => '_PhpScopera143bcca66cb\\OtherNamespace\\Ipsum', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'getIpsumWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => '_PhpScopera143bcca66cb\\OtherNamespace\\Ipsum', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'getIpsumStaticallyWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => '_PhpScopera143bcca66cb\\OtherNamespace\\Ipsum', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomethingStatically' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => 'void', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomethingWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomethingStaticallyWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => 'void', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomethingNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'doSomethingStaticallyNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => 'void', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'doSomethingWithDescriptionNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'doSomethingStaticallyWithDescriptionNoParams' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => 'void', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'methodFromTrait' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, 'returnType' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []]]);
        $bazBazMethods = \array_merge($bazMethods, ['getTest' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => '_PhpScopera143bcca66cb\\OtherNamespace\\Test', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getTestStatically' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => '_PhpScopera143bcca66cb\\OtherNamespace\\Test', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'getTestWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => '_PhpScopera143bcca66cb\\OtherNamespace\\Test', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => []], 'getTestStaticallyWithDescription' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => '_PhpScopera143bcca66cb\\OtherNamespace\\Test', 'isStatic' => \true, 'isVariadic' => \false, 'parameters' => []], 'doSomethingWithSpecificScalarParamsWithoutDefault' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'int|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'c', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createCreatesNewVariable(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'd', 'type' => 'int|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createCreatesNewVariable(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomethingWithSpecificScalarParamsWithDefault' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'int|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \false], ['name' => 'b', 'type' => 'int|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \false], ['name' => 'c', 'type' => 'int|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createCreatesNewVariable(), 'isOptional' => \true, 'isVariadic' => \false], ['name' => 'd', 'type' => 'int|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createCreatesNewVariable(), 'isOptional' => \true, 'isVariadic' => \false]]], 'doSomethingWithSpecificObjectParamsWithoutDefault' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => '_PhpScopera143bcca66cb\\OtherNamespace\\Ipsum', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'OtherNamespace\\Ipsum|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'c', 'type' => '_PhpScopera143bcca66cb\\OtherNamespace\\Ipsum', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createCreatesNewVariable(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'd', 'type' => 'OtherNamespace\\Ipsum|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createCreatesNewVariable(), 'isOptional' => \false, 'isVariadic' => \false]]], 'doSomethingWithSpecificObjectParamsWithDefault' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'OtherNamespace\\Ipsum|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \false], ['name' => 'b', 'type' => 'OtherNamespace\\Ipsum|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \false], ['name' => 'c', 'type' => 'OtherNamespace\\Ipsum|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createCreatesNewVariable(), 'isOptional' => \true, 'isVariadic' => \false], ['name' => 'd', 'type' => 'OtherNamespace\\Ipsum|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createCreatesNewVariable(), 'isOptional' => \true, 'isVariadic' => \false]]], 'doSomethingWithSpecificVariadicScalarParamsNotNullable' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \true, 'parameters' => [['name' => 'a', 'type' => 'int', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \true]]], 'doSomethingWithSpecificVariadicScalarParamsNullable' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \true, 'parameters' => [['name' => 'a', 'type' => 'int|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \true]]], 'doSomethingWithSpecificVariadicObjectParamsNotNullable' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \true, 'parameters' => [['name' => 'a', 'type' => '_PhpScopera143bcca66cb\\OtherNamespace\\Ipsum', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \true]]], 'doSomethingWithSpecificVariadicObjectParamsNullable' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \true, 'parameters' => [['name' => 'a', 'type' => 'OtherNamespace\\Ipsum|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \true]]], 'doSomethingWithComplicatedParameters' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'void', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'a', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'b', 'type' => 'mixed', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \false], ['name' => 'c', 'type' => 'bool|float|int|OtherNamespace\\Test|string', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'd', 'type' => 'bool|float|int|OtherNamespace\\Test|string|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \true, 'isVariadic' => \false]]], 'paramMultipleTypesWithExtraSpaces' => ['class' => \_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, 'returnType' => 'float|int', 'isStatic' => \false, 'isVariadic' => \false, 'parameters' => [['name' => 'string', 'type' => 'string|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false], ['name' => 'object', 'type' => 'OtherNamespace\\Test|null', 'passedByReference' => \PHPStan\Reflection\PassedByReference::createNo(), 'isOptional' => \false, 'isVariadic' => \false]]]]);
        return [[\_PhpScopera143bcca66cb\AnnotationsMethods\Foo::class, $fooMethods], [\_PhpScopera143bcca66cb\AnnotationsMethods\Bar::class, $barMethods], [\_PhpScopera143bcca66cb\AnnotationsMethods\Baz::class, $bazMethods], [\_PhpScopera143bcca66cb\AnnotationsMethods\BazBaz::class, $bazBazMethods]];
    }
    /**
     * @dataProvider dataMethods
     * @param string $className
     * @param array<string, mixed> $methods
     */
    public function testMethods(string $className, array $methods) : void
    {
        /** @var Broker $broker */
        $broker = self::getContainer()->getByType(\PHPStan\Broker\Broker::class);
        $class = $broker->getClass($className);
        $scope = $this->createMock(\PHPStan\Analyser\Scope::class);
        $scope->method('isInClass')->willReturn(\true);
        $scope->method('getClassReflection')->willReturn($class);
        $scope->method('canCallMethod')->willReturn(\true);
        foreach ($methods as $methodName => $expectedMethodData) {
            $this->assertTrue($class->hasMethod($methodName), \sprintf('Method %s() not found in class %s.', $methodName, $className));
            $method = $class->getMethod($methodName, $scope);
            $selectedParametersAcceptor = \PHPStan\Reflection\ParametersAcceptorSelector::selectSingle($method->getVariants());
            $this->assertSame($expectedMethodData['class'], $method->getDeclaringClass()->getName(), \sprintf('Declaring class of method %s() does not match.', $methodName));
            $this->assertSame($expectedMethodData['returnType'], $selectedParametersAcceptor->getReturnType()->describe(\PHPStan\Type\VerbosityLevel::precise()), \sprintf('Return type of method %s::%s() does not match', $className, $methodName));
            $this->assertSame($expectedMethodData['isStatic'], $method->isStatic(), \sprintf('Scope of method %s::%s() does not match', $className, $methodName));
            $this->assertSame($expectedMethodData['isVariadic'], $selectedParametersAcceptor->isVariadic(), \sprintf('Method %s::%s() does not match expected variadicity', $className, $methodName));
            $this->assertCount(\count($expectedMethodData['parameters']), $selectedParametersAcceptor->getParameters(), \sprintf('Method %s::%s() does not match expected count of parameters', $className, $methodName));
            foreach ($selectedParametersAcceptor->getParameters() as $i => $parameter) {
                $this->assertSame($expectedMethodData['parameters'][$i]['name'], $parameter->getName());
                $this->assertSame($expectedMethodData['parameters'][$i]['type'], $parameter->getType()->describe(\PHPStan\Type\VerbosityLevel::precise()));
                $this->assertTrue($expectedMethodData['parameters'][$i]['passedByReference']->equals($parameter->passedByReference()));
                $this->assertSame($expectedMethodData['parameters'][$i]['isOptional'], $parameter->isOptional());
                $this->assertSame($expectedMethodData['parameters'][$i]['isVariadic'], $parameter->isVariadic());
            }
        }
    }
    public function testOverridingNativeMethodsWithAnnotationsDoesNotBreakGetNativeMethod() : void
    {
        $broker = self::getContainer()->getByType(\PHPStan\Broker\Broker::class);
        $class = $broker->getClass(\_PhpScopera143bcca66cb\AnnotationsMethods\Bar::class);
        $this->assertTrue($class->hasNativeMethod('overridenMethodWithAnnotation'));
        $this->assertInstanceOf(\PHPStan\Reflection\Php\PhpMethodReflection::class, $class->getNativeMethod('overridenMethodWithAnnotation'));
    }
}
