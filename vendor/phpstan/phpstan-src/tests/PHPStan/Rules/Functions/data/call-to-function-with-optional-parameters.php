<?php

namespace _PhpScoper006a73f0e455\CallToFunctionWithOptionalParameters;

foo(1);
foo(1, 2);
foo(1, 2, 3);
\get_class();
\get_class(null);
/** @var object|null $object */
$object = doFoo();
\get_class($object);
