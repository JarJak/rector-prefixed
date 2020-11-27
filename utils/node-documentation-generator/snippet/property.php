<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\VarLikeIdentifier;
$propertyProperty = new \PhpParser\Node\Stmt\PropertyProperty(new \PhpParser\Node\VarLikeIdentifier('propertyName'));
return new \PhpParser\Node\Stmt\Property(\PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC, [$propertyProperty]);
