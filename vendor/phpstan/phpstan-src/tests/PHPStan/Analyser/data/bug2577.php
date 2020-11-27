<?php

namespace _PhpScoper006a73f0e455\Analyser\Bug2577;

use function PHPStan\Analyser\assertType;
class A
{
}
class A1 extends \_PhpScoper006a73f0e455\Analyser\Bug2577\A
{
}
class A2 extends \_PhpScoper006a73f0e455\Analyser\Bug2577\A
{
}
/**
 * @template T of A
 *
 * @param \Closure():T $t1
 * @param T $t2
 * @return T
 */
function echoOneOrOther(\Closure $t1, \_PhpScoper006a73f0e455\Analyser\Bug2577\A $t2)
{
    echo \get_class($t1());
    echo \get_class($t2);
    throw new \Exception();
}
function test() : void
{
    $result = echoOneOrOther(function () : A1 {
        return new \_PhpScoper006a73f0e455\Analyser\Bug2577\A1();
    }, new \_PhpScoper006a73f0e455\Analyser\Bug2577\A2());
    \PHPStan\Analyser\assertType('_PhpScoper006a73f0e455\\Analyser\\Bug2577\\A1|Analyser\\Bug2577\\A2', $result);
}