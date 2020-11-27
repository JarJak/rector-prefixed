<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
$dimension = new \PhpParser\Node\Scalar\LNumber(0);
return new \PhpParser\Node\Expr\ArrayDimFetch($variable, $dimension);