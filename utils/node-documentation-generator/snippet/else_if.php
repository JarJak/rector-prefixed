<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Return_;
$name = new \PhpParser\Node\Name('true');
$constFetch = new \PhpParser\Node\Expr\ConstFetch($name);
$stmt = new \PhpParser\Node\Stmt\Return_();
return new \PhpParser\Node\Stmt\ElseIf_($constFetch, [$stmt]);
