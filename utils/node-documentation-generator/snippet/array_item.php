<?php

declare (strict_types=1);
namespace _PhpScopera143bcca66cb;

use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
$value = new \PhpParser\Node\Expr\Variable('Tom');
$key = new \PhpParser\Node\Scalar\String_('name');
return new \PhpParser\Node\Expr\ArrayItem($value, $key);
