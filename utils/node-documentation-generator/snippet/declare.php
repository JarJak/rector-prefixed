<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;
$declareDeclare = new \PhpParser\Node\Stmt\DeclareDeclare('strict_types', new \PhpParser\Node\Scalar\LNumber(1));
return new \PhpParser\Node\Stmt\Declare_([$declareDeclare]);
