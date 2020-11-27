<?php

namespace _PhpScopera143bcca66cb\Analyser\Bug2954;

use function PHPStan\Analyser\assertType;
function (int $x) {
    if ($x === 0) {
        return;
    }
    \PHPStan\Analyser\assertType('int<min, -1>|int<1, max>', $x);
    $x++;
    \PHPStan\Analyser\assertType('int', $x);
};
function (int $x) {
    if ($x === 0) {
        return;
    }
    \PHPStan\Analyser\assertType('int<min, -1>|int<1, max>', $x);
    ++$x;
    \PHPStan\Analyser\assertType('int', $x);
};
function (int $x) {
    if ($x === 0) {
        return;
    }
    \PHPStan\Analyser\assertType('int<min, -1>|int<1, max>', $x);
    $x--;
    \PHPStan\Analyser\assertType('int', $x);
};
function (int $x) {
    if ($x === 0) {
        return;
    }
    \PHPStan\Analyser\assertType('int<min, -1>|int<1, max>', $x);
    --$x;
    \PHPStan\Analyser\assertType('int', $x);
};
