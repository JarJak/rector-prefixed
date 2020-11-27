<?php

declare (strict_types=1);
namespace Rector\PHPStanExtensions\Utils;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
final class PHPStanValueResolver
{
    public function resolveClassConstFetch(\PhpParser\Node\Expr\ClassConstFetch $classConstFetch) : ?string
    {
        $value = null;
        if ($classConstFetch->class instanceof \PhpParser\Node\Name) {
            $value = $classConstFetch->class->toString();
        } else {
            return null;
        }
        if ($classConstFetch->name instanceof \PhpParser\Node\Identifier) {
            $value .= '::' . $classConstFetch->name->toString();
        } else {
            return null;
        }
        return $value;
    }
}
