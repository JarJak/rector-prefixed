<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455;

use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\Variable;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
return new \PhpParser\Node\Expr\ClosureUse($variable);
