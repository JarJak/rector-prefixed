<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05;

use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\Variable;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
return new \PhpParser\Node\Expr\NullsafeMethodCall($variable, 'methodName');
