<?php

namespace _PhpScoper006a73f0e455;

function () {
    $array = [1, 2, 3, [4, 5]];
    [$a, $b, $c, [$d, $e]] = $array;
    foreach ($array as [$destructuredA, $destructuredB, [$destructuredC, $destructuredD]]) {
        echo $destructuredA, $destructuredB, $destructuredC, $destructuredD;
    }
    $anotherArray = [$f];
    echo $anotherArray;
    echo $f;
    if (\true) {
        [$var1] = [1];
        list($var2) = [1];
    } elseif (\true) {
        $var1 = 1;
        $var2 = 1;
    } else {
        $var1 = 1;
        $var2 = 2;
        [$var3] = 1;
    }
    [$var4] = [1];
    echo $var1;
    echo $var2;
    echo $var3;
    echo $var4;
    [$g, $h, $i] = [$j, $k, $l] = \_PhpScoper006a73f0e455\doFoo();
    echo $g;
    echo $h;
    echo $i;
    echo $j;
    echo $k;
    echo $l;
    list($m) = [$n] = \_PhpScoper006a73f0e455\doFoo();
    echo $m;
    echo $n;
    [, $variableInListWithMissingItem] = [, $anotherVariableInListWithMissingItem] = $array;
    echo $variableInListWithMissingItem;
    echo $anotherVariableInListWithMissingItem;
};
