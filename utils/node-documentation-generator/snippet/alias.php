<?php

declare (strict_types=1);
namespace _PhpScopera143bcca66cb;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUseAdaptation\Alias;
$traitFullyQualified = new \PhpParser\Node\Name\FullyQualified('TraitName');
return new \PhpParser\Node\Stmt\TraitUseAdaptation\Alias($traitFullyQualified, 'method', \PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC, 'aliasedMethod');
