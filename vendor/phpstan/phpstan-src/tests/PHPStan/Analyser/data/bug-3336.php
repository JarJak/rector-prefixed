<?php

namespace _PhpScoper006a73f0e455\Bug3336;

function (array $arr, string $str, $mixed) : void {
    \PHPStan\Analyser\assertType('array<int, string>', \mb_convert_encoding($arr));
    \PHPStan\Analyser\assertType('string', \mb_convert_encoding($str));
    \PHPStan\Analyser\assertType('array<int, string>|string', \mb_convert_encoding($mixed));
    \PHPStan\Analyser\assertType('array<int, string>|string', \mb_convert_encoding());
};
