<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
$class = new \PhpParser\Node\Name\FullyQualified('SomeClassName');
return new \PhpParser\Node\Expr\ClassConstFetch($class, 'SOME_CONSTANT');
