<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
$class = new \PhpParser\Node\Stmt\Class_('ClassName');
$class->flags = \PhpParser\Node\Stmt\Class_::MODIFIER_FINAL;
$class->extends = new \PhpParser\Node\Name\FullyQualified('ParentClass');
return $class;
