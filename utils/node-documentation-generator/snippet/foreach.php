<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Foreach_;
$foreachedVariable = new \PhpParser\Node\Expr\Variable('foreachedVariableName');
$asVariable = new \PhpParser\Node\Expr\Variable('asVariable');
return new \PhpParser\Node\Stmt\Foreach_($foreachedVariable, $asVariable);
