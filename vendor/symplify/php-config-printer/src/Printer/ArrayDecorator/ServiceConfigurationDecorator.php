<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\Printer\ArrayDecorator;

use _PhpScopere8e811afab72\PhpParser\Node\Arg;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Array_;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayItem;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall;
use _PhpScopere8e811afab72\PhpParser\Node\Name\FullyQualified;
use _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\NodeFactory\NewValueObjectFactory;
use _PhpScopere8e811afab72\Symplify\PhpConfigPrinter\Reflection\ConstantNameFromValueResolver;
use _PhpScopere8e811afab72\Symplify\SymfonyPhpConfig\ValueObjectInliner;
final class ServiceConfigurationDecorator
{
    /**
     * @var ConstantNameFromValueResolver
     */
    private $constantNameFromValueResolver;
    /**
     * @var NewValueObjectFactory
     */
    private $newValueObjectFactory;
    public function __construct(\_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\Reflection\ConstantNameFromValueResolver $constantNameFromValueResolver, \_PhpScopere8e811afab72\Symplify\PhpConfigPrinter\NodeFactory\NewValueObjectFactory $newValueObjectFactory)
    {
        $this->constantNameFromValueResolver = $constantNameFromValueResolver;
        $this->newValueObjectFactory = $newValueObjectFactory;
    }
    /**
     * @param mixed|mixed[] $configuration
     * @return mixed|mixed[]
     */
    public function decorate($configuration, string $class)
    {
        if (!\is_array($configuration)) {
            return $configuration;
        }
        $configuration = $this->decorateClassConstantKeys($configuration, $class);
        foreach ($configuration as $key => $value) {
            if ($this->isArrayOfObjects($value)) {
                $configuration[$key] = $this->decorateValueObjects($value);
            } elseif (\is_object($value)) {
                $configuration[$key] = $this->decorateValueObject($value);
            }
        }
        return $configuration;
    }
    /**
     * @param mixed[] $configuration
     * @return mixed[]
     */
    private function decorateClassConstantKeys(array $configuration, string $class) : array
    {
        foreach ($configuration as $key => $value) {
            $constantName = $this->constantNameFromValueResolver->resolveFromValueAndClass($key, $class);
            if ($constantName === null) {
                continue;
            }
            unset($configuration[$key]);
            $classConstantReference = $class . '::' . $constantName;
            $configuration[$classConstantReference] = $value;
        }
        return $configuration;
    }
    private function decorateValueObject(object $value) : \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall
    {
        $new = $this->newValueObjectFactory->create($value);
        $args = [new \_PhpScopere8e811afab72\PhpParser\Node\Arg($new)];
        return $this->createInlineStaticCall($args);
    }
    private function decorateValueObjects(array $values) : \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall
    {
        $arrayItems = [];
        foreach ($values as $value) {
            $new = $this->newValueObjectFactory->create($value);
            $arrayItems[] = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\ArrayItem($new);
        }
        $array = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Array_($arrayItems);
        $args = [new \_PhpScopere8e811afab72\PhpParser\Node\Arg($array)];
        return $this->createInlineStaticCall($args);
    }
    private function isArrayOfObjects($values) : bool
    {
        if (!\is_array($values)) {
            return \false;
        }
        if ($values === []) {
            return \false;
        }
        foreach ($values as $value) {
            if (!\is_object($value)) {
                return \false;
            }
        }
        return \true;
    }
    /**
     * Depends on symplify/symfony-php-config
     *
     * @param Arg[] $args
     */
    private function createInlineStaticCall(array $args) : \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall
    {
        $fullyQualified = new \_PhpScopere8e811afab72\PhpParser\Node\Name\FullyQualified(\_PhpScopere8e811afab72\Symplify\SymfonyPhpConfig\ValueObjectInliner::class);
        return new \_PhpScopere8e811afab72\PhpParser\Node\Expr\StaticCall($fullyQualified, 'inline', $args);
    }
}
