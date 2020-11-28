<?php

declare (strict_types=1);
namespace _PhpScoperabd03f0baf05;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
$propertyProperties = [new \PhpParser\Node\Stmt\PropertyProperty('firstProperty'), new \PhpParser\Node\Stmt\PropertyProperty('secondProperty')];
return new \PhpParser\Node\Stmt\Property(\PhpParser\Node\Stmt\Class_::MODIFIER_STATIC | \PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC, $propertyProperties);
