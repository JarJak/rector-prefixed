<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\If_;
$cond = new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('true'));
return new \PhpParser\Node\Stmt\If_($cond);