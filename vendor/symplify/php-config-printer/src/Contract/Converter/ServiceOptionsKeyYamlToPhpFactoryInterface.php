<?php

declare (strict_types=1);
namespace RectorPrefix20210118\Symplify\PhpConfigPrinter\Contract\Converter;

use PhpParser\Node\Expr\MethodCall;
interface ServiceOptionsKeyYamlToPhpFactoryInterface
{
    public function decorateServiceMethodCall($key, $yaml, $values, \PhpParser\Node\Expr\MethodCall $serviceMethodCall) : \PhpParser\Node\Expr\MethodCall;
    public function isMatch($key, $values) : bool;
}
