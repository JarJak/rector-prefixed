<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Scalar\LNumber;
$left = new \PhpParser\Node\Scalar\LNumber(5);
$right = new \PhpParser\Node\Scalar\LNumber(10);
return new \PhpParser\Node\Expr\BinaryOp\Identical($left, $right);
