<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\CodeQuality\Naming;

use _PhpScoper0a2ac50786fa\Nette\Utils\Strings;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ClassConstFetch;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Identifier;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Name;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\String_;
use _PhpScoper0a2ac50786fa\Rector\Naming\Naming\ExpectedNameResolver;
use _PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver;
final class MethodCallToVariableNameResolver
{
    /**
     * @var string
     * @see https://regex101.com/r/LTykey/1
     */
    private const START_ALPHA_REGEX = '#^[a-zA-Z]#';
    /**
     * @var string
     * @see https://regex101.com/r/sYIKpj/1
     */
    private const CONSTANT_REGEX = '#(_)([a-z])#';
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var ExpectedNameResolver
     */
    private $expectedNameResolver;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \_PhpScoper0a2ac50786fa\Rector\Naming\Naming\ExpectedNameResolver $expectedNameResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->expectedNameResolver = $expectedNameResolver;
    }
    /**
     * @todo decouple to collector by arg type
     */
    public function resolveVariableName(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall $methodCall) : ?string
    {
        $methodCallVarName = $this->nodeNameResolver->getName($methodCall->var);
        $methodCallName = $this->nodeNameResolver->getName($methodCall->name);
        if ($methodCallVarName === null || $methodCallName === null) {
            return null;
        }
        return $this->getVariableName($methodCall, $methodCallVarName, $methodCallName);
    }
    private function getVariableName(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\MethodCall $methodCall, string $methodCallVarName, string $methodCallName) : string
    {
        $variableName = $this->expectedNameResolver->resolveForCall($methodCall);
        if ($methodCall->args === [] && $variableName !== null && $variableName !== $methodCallVarName) {
            return $variableName;
        }
        $fallbackVarName = $this->getFallbackVarName($methodCallVarName, $methodCallName);
        $argValue = $methodCall->args[0]->value;
        if ($argValue instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ClassConstFetch && $argValue->name instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Identifier) {
            return $this->getClassConstFetchVarName($argValue, $methodCallName);
        }
        if ($argValue instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\String_) {
            return $this->getStringVarName($argValue, $methodCallVarName, $fallbackVarName);
        }
        $argumentName = $this->nodeNameResolver->getName($argValue);
        if ($argValue instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable && $argumentName !== null && $variableName !== null) {
            return $argumentName . \ucfirst($variableName);
        }
        return $fallbackVarName;
    }
    private function getFallbackVarName(string $methodCallVarName, string $methodCallName) : string
    {
        return $methodCallVarName . \ucfirst($methodCallName);
    }
    private function getClassConstFetchVarName(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ClassConstFetch $classConstFetch, string $methodCallName) : string
    {
        /** @var Identifier $name */
        $name = $classConstFetch->name;
        $argValueName = \strtolower($name->toString());
        if ($argValueName !== 'class') {
            return \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::replace($argValueName, self::CONSTANT_REGEX, function ($matches) : string {
                return \strtoupper($matches[2]);
            });
        }
        if ($classConstFetch->class instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Name) {
            return $this->normalizeStringVariableName($methodCallName) . $classConstFetch->class->getLast();
        }
        return $this->normalizeStringVariableName($methodCallName);
    }
    private function getStringVarName(\_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\String_ $string, string $methodCallVarName, string $fallbackVarName) : string
    {
        $normalizeStringVariableName = $this->normalizeStringVariableName($string->value . \ucfirst($fallbackVarName));
        if (\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::match($normalizeStringVariableName, self::START_ALPHA_REGEX) && $normalizeStringVariableName !== $methodCallVarName) {
            return $normalizeStringVariableName;
        }
        return $fallbackVarName;
    }
    private function normalizeStringVariableName(string $string) : string
    {
        $get = \str_ireplace('get', '', $string);
        $by = \str_ireplace('by', '', $get);
        return \str_replace('-', '', $by);
    }
}
