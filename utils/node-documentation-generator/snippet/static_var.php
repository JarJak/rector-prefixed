<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\StaticVar;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
return new \PhpParser\Node\Stmt\StaticVar($variable);
