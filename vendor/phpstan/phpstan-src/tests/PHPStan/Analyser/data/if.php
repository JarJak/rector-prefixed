<?php

namespace _PhpScopera143bcca66cb;

function () {
    if (\_PhpScopera143bcca66cb\foo()) {
        $ifVar = 1;
        $issetFoo = new \_PhpScopera143bcca66cb\Foo();
        $maybeDefinedButLaterCertainlyDefined = 1;
        if ($test) {
            $ifNestedVar = 1;
            $ifNotNestedVar = 1;
        } elseif (\_PhpScopera143bcca66cb\fooBar()) {
            $ifNotNestedVar = 2;
            $variableOnlyInEarlyTerminatingElse = 1;
            throw $e;
        } else {
            $ifNestedVar = 2;
        }
        $ifNotVar = 1;
    } elseif (\_PhpScopera143bcca66cb\bar()) {
        $ifVar = 2;
        $issetFoo = null;
        $ifNestedVar = 2;
        $ifNotNestedVar = 2;
        $ifNotVar = 2;
    } elseif ($ifNestedVar = 3) {
        $ifVar = 3;
        $ifNotNestedVar = 3;
    } else {
        $variableOnlyInEarlyTerminatingElse = 1;
        return;
    }
    if (\_PhpScopera143bcca66cb\foo()) {
        $maybeDefinedButLaterCertainlyDefined = 2;
    } else {
        $maybeDefinedButLaterCertainlyDefined = 3;
    }
    $exceptionFromTry = null;
    try {
        $inTry = 1;
        $inTryNotInCatch = 1;
        $fooObjectFromTryCatch = new \_PhpScopera143bcca66cb\InTryCatchFoo();
        $mixedVarFromTryCatch = 1;
        $nullableIntegerFromTryCatch = 1;
        $anotherNullableIntegerFromTryCatch = null;
        $someVariableThatWillGetOverrideInFinally = 1;
    } catch (\_PhpScopera143bcca66cb\SomeConcreteException $e) {
        $inTry = 1;
        $fooObjectFromTryCatch = new \_PhpScopera143bcca66cb\InTryCatchFoo();
        $mixedVarFromTryCatch = 1.0;
        $nullableIntegerFromTryCatch = null;
        $anotherNullableIntegerFromTryCatch = 1;
    } catch (\Exception $e) {
        throw $e;
    } finally {
        $someVariableThatWillGetOverrideInFinally = 'foo';
        \restore_error_handler();
    }
    $exceptionFromTryCatch = null;
    try {
    } catch (\_PhpScopera143bcca66cb\SomeConcreteException $exceptionFromTryCatch) {
        return;
    } catch (\_PhpScopera143bcca66cb\AnotherException $exceptionFromTryCatch) {
    } catch (\_PhpScopera143bcca66cb\YetAnotherException $exceptionFromTryCatch) {
        \_PhpScopera143bcca66cb\doFoo();
    }
    $lorem = 1;
    $arrOne[] = 'one';
    $arrTwo['test'] = 'two';
    $anotherArray['test'][] = 'another';
    \_PhpScopera143bcca66cb\doSomething($one, $callParameter = 3);
    $arrTwo[] = new \_PhpScopera143bcca66cb\Foo([$inArray = 1]);
    $arrThree = null;
    $arrThree[] = 'three';
    \preg_match('#.*#', 'foo', $matches);
    if ((bool) \preg_match('#.*#', 'foo', $matches3)) {
        \_PhpScopera143bcca66cb\foo();
    } elseif (\preg_match('#.*#', 'foo', $matches4)) {
        \_PhpScopera143bcca66cb\foo();
    }
    $trueOrFalseFromSwitch = \true;
    switch (\_PhpScopera143bcca66cb\foo()) {
        case 1:
            $switchVar = 1;
            $noSwitchVar = 1;
            $trueOrFalseFromSwitch = \false;
            break;
        case 'foo':
            $trueOrFalseFromSwitch = 1;
            return;
        case 2:
            $switchVar = 2;
            break;
        case 3:
            $anotherNoSwitchVar = 1;
        case 4:
        default:
            $switchVar = 3;
            if (\_PhpScopera143bcca66cb\doFoo()) {
                $switchVar = 4;
                break;
            }
    }
    $trueOrFalseInSwitchWithDefault = \false;
    $nullableTrueOrFalse = null;
    switch ('foo') {
        case 'foo':
            $trueOrFalseInSwitchWithDefault = \true;
            $nullableTrueOrFalse = \true;
            continue;
        case 'bar':
            $nullableTrueOrFalse = \false;
            break;
        default:
            break;
    }
    $trueOrFalseInSwitchInAllCases = \false;
    switch ('foo') {
        case 'foo':
            $trueOrFalseInSwitchInAllCases = \true;
            break;
        case 'bar':
            $trueOrFalseInSwitchInAllCases = \true;
            break;
    }
    $trueOrFalseInSwitchInAllCasesWithDefault = \false;
    switch ('foo') {
        case 'foo':
            $trueOrFalseInSwitchInAllCasesWithDefault = \true;
            break;
        case 'bar':
            $trueOrFalseInSwitchInAllCasesWithDefault = \true;
            break;
        default:
            break;
    }
    $trueOrFalseInSwitchInAllCasesWithDefaultCase = \false;
    switch ('foo') {
        case 'foo':
            $trueOrFalseInSwitchInAllCasesWithDefaultCase = \true;
            break;
        case 'bar':
            $trueOrFalseInSwitchInAllCasesWithDefaultCase = \true;
            break;
        default:
            $trueOrFalseInSwitchInAllCasesWithDefaultCase = \true;
            break;
    }
    switch ('foo') {
        case 'foo':
            $variableDefinedInSwitchWithOtherCasesWithEarlyTermination = \true;
            break;
        case 'bar':
            throw new \Exception();
        default:
            throw new \Exception();
    }
    switch ('foo') {
        case 'foo':
            throw new \Exception();
        case 'bar':
            $anotherVariableDefinedInSwitchWithOtherCasesWithEarlyTermination = \true;
            break;
        default:
            throw new \Exception();
    }
    switch ('foo') {
        case 'foo':
            $variableDefinedOnlyInEarlyTerminatingSwitchCases = \true;
            throw new \Exception();
        case 'bar':
            $variableDefinedOnlyInEarlyTerminatingSwitchCases = \true;
            return;
        case 'baz':
            break;
        default:
            $variableDefinedOnlyInEarlyTerminatingSwitchCases = \true;
            return;
    }
    switch ('foo') {
        case 'a':
            $variableDefinedInSwitchWithoutEarlyTermination = \true;
        case 'b':
            $variableDefinedInSwitchWithoutEarlyTermination = \false;
    }
    switch ('foo') {
        case 'a':
            $anotherVariableDefinedInSwitchWithoutEarlyTermination = \true;
            break;
        case 'b':
            $anotherVariableDefinedInSwitchWithoutEarlyTermination = \false;
    }
    switch (\_PhpScopera143bcca66cb\doFoo()) {
        case 1:
        case 2:
        case 3:
            $alwaysDefinedFromSwitch = 1;
            break;
        default:
            $alwaysDefinedFromSwitch = null;
    }
    $nullOverwrittenInSwitchToOne = null;
    switch (\_PhpScopera143bcca66cb\doFoo()) {
        case 1:
            if (\_PhpScopera143bcca66cb\doFoo()) {
                throw new \Exception();
            }
            $nullOverwrittenInSwitchToOne = 1;
            break;
        default:
            throw new \Exception();
    }
    switch (\_PhpScopera143bcca66cb\doFoo()) {
        case 1:
            if (\rand(0, 1)) {
                $variableFromSwitchShouldBeBool = \true;
                break;
            }
        default:
            $variableFromSwitchShouldBeBool = \false;
    }
    do {
        $doWhileVar = 1;
    } while (\_PhpScopera143bcca66cb\something());
    $integerOrNullFromFor = null;
    for ($previousI = 0, $previousJ = 0; $previousI < 1; $previousI++) {
        $integerOrNullFromFor = 1;
        $nonexistentVariableOutsideFor = 1;
    }
    $integerOrNullFromWhile = null;
    while (($frame = $that->getReader()->consumeFrame($that->getReadBuffer())) === null) {
        $integerOrNullFromWhile = 1;
        $nonexistentVariableOutsideWhile = 1;
    }
    /** @var array $someArray */
    $someArray = \_PhpScopera143bcca66cb\doFoo();
    $integerOrNullFromForeach = null;
    foreach ($someArray as $someValue) {
        $integerOrNullFromForeach = 1;
        $nonexistentVariableOutsideForeach = null;
    }
    $nullableIntegers = [1, 2, 3];
    $nullableIntegers[] = null;
    $union = [1, 2, 3];
    $union[] = 'foo';
    ${$lorem} = 'ipsum';
    $trueOrFalse = \true;
    $falseOrTrue = \false;
    $true = \true;
    $false = \false;
    if (\_PhpScopera143bcca66cb\doFoo()) {
        $trueOrFalse = \false;
        $falseOrTrue = \true;
        $true = \true;
        $false = \false;
    }
    /** @var string|null $notNullableString */
    $notNullableString = 'foo';
    if ($notNullableString === null) {
        return;
    }
    /** @var string|null $anotherNotNullableString */
    $anotherNotNullableString = 'foo';
    if ($anotherNotNullableString !== null) {
        $alsoNotNullableString = $anotherNotNullableString;
    } else {
        return;
    }
    /** @var Foo|null $notNullableObject */
    $notNullableObject = \_PhpScopera143bcca66cb\doFoo();
    if ($notNullableObject === null) {
        $notNullableObject = new \_PhpScopera143bcca66cb\Foo();
    }
    /** @var string|null $nullableString */
    $nullableString = 'foo';
    if ($nullableString !== null) {
        $whatever = $nullableString;
    }
    /** @var int|null $integerOrString */
    $integerOrString = 1;
    if ($integerOrString === null) {
        $integerOrString = 'str';
    }
    /** @var int|null $stillNullableInteger */
    $stillNullableInteger = 1;
    if (\is_int($stillNullableInteger)) {
        $stillNullableInteger = 2;
    }
    /** @var int|null $nullableIntegerAfterNeverCondition */
    $nullableIntegerAfterNeverCondition = 1;
    if ($nullableIntegerAfterNeverCondition === \false) {
        $nullableIntegerAfterNeverCondition = 1;
    }
    $arrayOfIntegers = [1, 2, 3];
    $arrayAccessObject = new \_PhpScopera143bcca66cb\ObjectWithArrayAccess\Foo();
    $arrayAccessObject[] = 1;
    $arrayAccessObject[] = 2;
    $width = 1;
    $scale = 2.0;
    $width *= $scale;
    /** @var mixed $mixed */
    $mixed = \_PhpScopera143bcca66cb\doFoo();
    if (\is_bool($mixed)) {
        $mixed = 1;
    }
    if (\rand(0, 1)) {
        /** @var mixed $issetBar */
        $issetBar = \_PhpScopera143bcca66cb\doFoo();
        /** @var mixed $issetBaz */
        $issetBaz = \_PhpScopera143bcca66cb\doFoo();
    }
    try {
        $inTryTwo = 1;
    } catch (\Exception $e) {
        $exception = $e;
        if (\_PhpScopera143bcca66cb\something()) {
            \_PhpScopera143bcca66cb\bar();
        } elseif (\_PhpScopera143bcca66cb\foo() || ($foo = \_PhpScopera143bcca66cb\exists() || \preg_match('#.*#', $subject, $matches2))) {
            if (isset($issetFoo, $issetBar) && isset($issetBaz)) {
                $anotherF = 1;
                for ($i = 0; $i < 5; $i++, $f = $i, $anotherF = $i) {
                    $arr = [[1, 2]];
                    foreach ($arr as list($listOne, $listTwo)) {
                        if (\is_array($arrayOfIntegers)) {
                            (bool) \preg_match('~.*~', $attributes, $ternaryMatches) ? die : null;
                        }
                    }
                }
            }
        }
    }
};
