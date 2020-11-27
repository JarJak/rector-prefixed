<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\Nette\DI;

use _PhpScoper26e51eeacccf\Nette;
use _PhpScoper26e51eeacccf\Nette\DI\Definitions\Definition;
use _PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference;
use _PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement;
use _PhpScoper26e51eeacccf\Nette\PhpGenerator\Helpers as PhpHelpers;
use _PhpScoper26e51eeacccf\Nette\Utils\Reflection;
use _PhpScoper26e51eeacccf\Nette\Utils\Strings;
use _PhpScoper26e51eeacccf\Nette\Utils\Validators;
use ReflectionClass;
/**
 * Services resolver
 * @internal
 */
class Resolver
{
    use Nette\SmartObject;
    /** @var ContainerBuilder */
    private $builder;
    /** @var Definition|null */
    private $currentService;
    /** @var string|null */
    private $currentServiceType;
    /** @var bool */
    private $currentServiceAllowed = \false;
    /** @var \SplObjectStorage  circular reference detector */
    private $recursive;
    public function __construct(\_PhpScoper26e51eeacccf\Nette\DI\ContainerBuilder $builder)
    {
        $this->builder = $builder;
        $this->recursive = new \SplObjectStorage();
    }
    public function getContainerBuilder() : \_PhpScoper26e51eeacccf\Nette\DI\ContainerBuilder
    {
        return $this->builder;
    }
    public function resolveDefinition(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Definition $def) : void
    {
        if ($this->recursive->contains($def)) {
            $names = \array_map(function ($item) {
                return $item->getName();
            }, \iterator_to_array($this->recursive));
            throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException(\sprintf('Circular reference detected for services: %s.', \implode(', ', $names)));
        }
        try {
            $this->recursive->attach($def);
            $def->resolveType($this);
            if (!$def->getType()) {
                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException('Type of service is unknown.');
            }
        } catch (\Exception $e) {
            throw $this->completeException($e, $def);
        } finally {
            $this->recursive->detach($def);
        }
    }
    public function resolveReferenceType(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference $ref) : ?string
    {
        if ($ref->isSelf()) {
            return $this->currentServiceType;
        } elseif ($ref->isType()) {
            return \ltrim($ref->getValue(), '\\');
        }
        $def = $this->resolveReference($ref);
        if (!$def->getType()) {
            $this->resolveDefinition($def);
        }
        return $def->getType();
    }
    public function resolveEntityType(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement $statement) : ?string
    {
        $entity = $this->normalizeEntity($statement);
        if (\is_array($entity)) {
            if ($entity[0] instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference || $entity[0] instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement) {
                $entity[0] = $this->resolveEntityType($entity[0] instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement ? $entity[0] : new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement($entity[0]));
                if (!$entity[0]) {
                    return null;
                }
            }
            try {
                /** @var \ReflectionMethod|\ReflectionFunction $reflection */
                $reflection = \_PhpScoper26e51eeacccf\Nette\Utils\Callback::toReflection($entity[0] === '' ? $entity[1] : $entity);
                $refClass = $reflection instanceof \ReflectionMethod ? $reflection->getDeclaringClass() : null;
            } catch (\ReflectionException $e) {
                $refClass = $reflection = null;
            }
            if (isset($e) || $refClass && (!$reflection->isPublic() || $refClass->isTrait() && !$reflection->isStatic())) {
                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException(\sprintf('Method %s() is not callable.', \_PhpScoper26e51eeacccf\Nette\Utils\Callback::toString($entity)), 0, $e ?? null);
            }
            $this->addDependency($reflection);
            $type = \_PhpScoper26e51eeacccf\Nette\DI\Helpers::getReturnType($reflection);
            if ($type && !\class_exists($type) && !\interface_exists($type)) {
                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException(\sprintf("Class or interface '%s' not found. Is return type of %s() correct?", $type, \_PhpScoper26e51eeacccf\Nette\Utils\Callback::toString($entity)));
            }
            return $type;
        } elseif ($entity instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference) {
            // alias or factory
            return $this->resolveReferenceType($entity);
        } elseif (\is_string($entity)) {
            // class
            if (!\class_exists($entity)) {
                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException(\interface_exists($entity) ? "Interface {$entity} can not be used as 'factory', did you mean 'implement'?" : "Class {$entity} not found.");
            }
            return $entity;
        }
        return null;
    }
    public function completeDefinition(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Definition $def) : void
    {
        $this->currentService = \in_array($def, $this->builder->getDefinitions(), \true) ? $def : null;
        $this->currentServiceType = $def->getType();
        $this->currentServiceAllowed = \false;
        try {
            $def->complete($this);
            $this->addDependency(new \ReflectionClass($def->getType()));
        } catch (\Exception $e) {
            throw $this->completeException($e, $def);
        } finally {
            $this->currentService = $this->currentServiceType = null;
        }
    }
    public function completeStatement(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement $statement, bool $currentServiceAllowed = \false) : \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement
    {
        $this->currentServiceAllowed = $currentServiceAllowed;
        $entity = $this->normalizeEntity($statement);
        $arguments = $this->convertReferences($statement->arguments);
        $getter = function (string $type, bool $single) {
            return $single ? $this->getByType($type) : \array_values(\array_filter($this->builder->findAutowired($type), function ($obj) {
                return $obj !== $this->currentService;
            }));
        };
        switch (\true) {
            case \is_string($entity) && \_PhpScoper26e51eeacccf\Nette\Utils\Strings::contains($entity, '?'):
                // PHP literal
                break;
            case $entity === 'not':
                if (\count($arguments) > 1) {
                    throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Function {$entity}() expects at most 1 parameter, " . \count($arguments) . ' given.');
                }
                $entity = ['', '!'];
                break;
            case $entity === 'bool':
            case $entity === 'int':
            case $entity === 'float':
            case $entity === 'string':
                if (\count($arguments) > 1) {
                    throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Function {$entity}() expects at most 1 parameter, " . \count($arguments) . ' given.');
                }
                $arguments = [$arguments[0], $entity];
                $entity = [\_PhpScoper26e51eeacccf\Nette\DI\Helpers::class, 'convertType'];
                break;
            case \is_string($entity):
                // create class
                if (!\class_exists($entity)) {
                    throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Class {$entity} not found.");
                } elseif ((new \ReflectionClass($entity))->isAbstract()) {
                    throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Class {$entity} is abstract.");
                } elseif (($rm = (new \ReflectionClass($entity))->getConstructor()) !== null && !$rm->isPublic()) {
                    $visibility = $rm->isProtected() ? 'protected' : 'private';
                    throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Class {$entity} has {$visibility} constructor.");
                } elseif ($constructor = (new \ReflectionClass($entity))->getConstructor()) {
                    $arguments = self::autowireArguments($constructor, $arguments, $getter);
                    $this->addDependency($constructor);
                } elseif ($arguments) {
                    throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Unable to pass arguments, class {$entity} has no constructor.");
                }
                break;
            case $entity instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference:
                $entity = [new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference(\_PhpScoper26e51eeacccf\Nette\DI\ContainerBuilder::THIS_CONTAINER), \_PhpScoper26e51eeacccf\Nette\DI\Container::getMethodName($entity->getValue())];
                break;
            case \is_array($entity):
                if (!\preg_match('#^\\$?(\\\\?' . \_PhpScoper26e51eeacccf\Nette\PhpGenerator\Helpers::PHP_IDENT . ')+(\\[\\])?$#D', $entity[1])) {
                    throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Expected function, method or property name, '{$entity[1]}' given.");
                }
                switch (\true) {
                    case $entity[0] === '':
                        // function call
                        if (!\_PhpScoper26e51eeacccf\Nette\Utils\Arrays::isList($arguments)) {
                            throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Unable to pass specified arguments to {$entity[0]}.");
                        } elseif (!\function_exists($entity[1])) {
                            throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Function {$entity[1]} doesn't exist.");
                        }
                        $rf = new \ReflectionFunction($entity[1]);
                        $arguments = self::autowireArguments($rf, $arguments, $getter);
                        $this->addDependency($rf);
                        break;
                    case $entity[0] instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement:
                        $entity[0] = $this->completeStatement($entity[0], $this->currentServiceAllowed);
                    // break omitted
                    case \is_string($entity[0]):
                    // static method call
                    case $entity[0] instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference:
                        if ($entity[1][0] === '$') {
                            // property getter, setter or appender
                            \_PhpScoper26e51eeacccf\Nette\Utils\Validators::assert($arguments, 'list:0..1', "setup arguments for '" . \_PhpScoper26e51eeacccf\Nette\Utils\Callback::toString($entity) . "'");
                            if (!$arguments && \substr($entity[1], -2) === '[]') {
                                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Missing argument for {$entity[1]}.");
                            }
                        } elseif ($type = $entity[0] instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference ? $this->resolveReferenceType($entity[0]) : $this->resolveEntityType($entity[0] instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement ? $entity[0] : new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement($entity[0]))) {
                            $rc = new \ReflectionClass($type);
                            if ($rc->hasMethod($entity[1])) {
                                $rm = $rc->getMethod($entity[1]);
                                if (!$rm->isPublic()) {
                                    throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("{$type}::{$entity[1]}() is not callable.");
                                }
                                $arguments = self::autowireArguments($rm, $arguments, $getter);
                                $this->addDependency($rm);
                            } elseif (!\_PhpScoper26e51eeacccf\Nette\Utils\Arrays::isList($arguments)) {
                                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Unable to pass specified arguments to {$type}::{$entity[1]}().");
                            }
                        }
                }
        }
        try {
            $arguments = $this->completeArguments($arguments);
        } catch (\_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException $e) {
            if (!\strpos($e->getMessage(), ' (used in')) {
                $e->setMessage($e->getMessage() . " (used in {$this->entityToString($entity)})");
            }
            throw $e;
        }
        return new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement($entity, $arguments);
    }
    public function completeArguments(array $arguments) : array
    {
        \array_walk_recursive($arguments, function (&$val) : void {
            if ($val instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement) {
                $entity = $val->getEntity();
                if ($entity === 'typed' || $entity === 'tagged') {
                    $services = [];
                    $current = $this->currentService ? $this->currentService->getName() : null;
                    foreach ($val->arguments as $argument) {
                        foreach ($entity === 'tagged' ? $this->builder->findByTag($argument) : $this->builder->findAutowired($argument) as $name => $foo) {
                            if ($name !== $current) {
                                $services[] = new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference($name);
                            }
                        }
                    }
                    $val = $this->completeArguments($services);
                } else {
                    $val = $this->completeStatement($val, $this->currentServiceAllowed);
                }
            } elseif ($val instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Definition || $val instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference) {
                $val = $this->normalizeEntity(new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement($val));
            }
        });
        return $arguments;
    }
    /** @return string|array|Reference  literal, Class, Reference, [Class, member], [, globalFunc], [Reference, member], [Statement, member] */
    private function normalizeEntity(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement $statement)
    {
        $entity = $statement->getEntity();
        if (\is_array($entity)) {
            $item =& $entity[0];
        } else {
            $item =& $entity;
        }
        if ($item instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Definition) {
            $name = \current(\array_keys($this->builder->getDefinitions(), $item, \true));
            if ($name === \false) {
                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Service '{$item->getName()}' not found in definitions.");
            }
            $item = new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference($name);
        }
        if ($item instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference) {
            $item = $this->normalizeReference($item);
        }
        return $entity;
    }
    /**
     * Normalizes reference to 'self' or named reference (or leaves it typed if it is not possible during resolving) and checks existence of service.
     */
    public function normalizeReference(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference $ref) : \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference
    {
        $service = $ref->getValue();
        if ($ref->isSelf()) {
            return $ref;
        } elseif ($ref->isName()) {
            if (!$this->builder->hasDefinition($service)) {
                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Reference to missing service '{$service}'.");
            }
            return $this->currentService && $service === $this->currentService->getName() ? new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference::SELF) : $ref;
        }
        try {
            return $this->getByType($service);
        } catch (\_PhpScoper26e51eeacccf\Nette\DI\NotAllowedDuringResolvingException $e) {
            return new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference($service);
        }
    }
    public function resolveReference(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference $ref) : \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Definition
    {
        return $ref->isSelf() ? $this->currentService : $this->builder->getDefinition($ref->getValue());
    }
    /**
     * Returns named reference to service resolved by type (or 'self' reference for local-autowiring).
     * @throws ServiceCreationException when multiple found
     * @throws MissingServiceException when not found
     */
    public function getByType(string $type) : \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference
    {
        if ($this->currentService && $this->currentServiceAllowed && \is_a($this->currentServiceType, $type, \true)) {
            return new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference(\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference::SELF);
        }
        $name = $this->builder->getByType($type, \true);
        if (!$this->currentServiceAllowed && $this->currentService === $this->builder->getDefinition($name)) {
            throw new \_PhpScoper26e51eeacccf\Nette\DI\MissingServiceException();
        }
        return new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference($name);
    }
    /**
     * Adds item to the list of dependencies.
     * @param  \ReflectionClass|\ReflectionFunctionAbstract|string  $dep
     * @return static
     */
    public function addDependency($dep)
    {
        $this->builder->addDependency($dep);
        return $this;
    }
    private function completeException(\Exception $e, \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Definition $def) : \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException
    {
        if ($e instanceof \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException && \_PhpScoper26e51eeacccf\Nette\Utils\Strings::startsWith($e->getMessage(), "Service '")) {
            return $e;
        }
        $name = $def->getName();
        $type = $def->getType();
        if ($name && !\ctype_digit($name)) {
            $message = "Service '{$name}'" . ($type ? " (type of {$type})" : '') . ': ';
        } elseif ($type) {
            $message = "Service of type {$type}: ";
        } elseif ($def instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\ServiceDefinition && $def->getEntity()) {
            $message = 'Service (' . $this->entityToString($def->getEntity()) . '): ';
        } else {
            $message = '';
        }
        $message .= $type ? \str_replace("{$type}::", '', $e->getMessage()) : $e->getMessage();
        return $e instanceof \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException ? $e->setMessage($message) : new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException($message, 0, $e);
    }
    private function entityToString($entity) : string
    {
        $referenceToText = function (\_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference $ref) : string {
            return $ref->isSelf() && $this->currentService ? '@' . $this->currentService->getName() : '@' . $ref->getValue();
        };
        if (\is_string($entity)) {
            return $entity . '::__construct()';
        } elseif ($entity instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference) {
            $entity = $referenceToText($entity);
        } elseif (\is_array($entity)) {
            if (\strpos($entity[1], '$') === \false) {
                $entity[1] .= '()';
            }
            if ($entity[0] instanceof \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference) {
                $entity[0] = $referenceToText($entity[0]);
            } elseif (!\is_string($entity[0])) {
                return $entity[1];
            }
            return \implode('::', $entity);
        }
        return (string) $entity;
    }
    private function convertReferences(array $arguments) : array
    {
        \array_walk_recursive($arguments, function (&$val) : void {
            if (\is_string($val) && \strlen($val) > 1 && $val[0] === '@' && $val[1] !== '@') {
                $pair = \explode('::', \substr($val, 1), 2);
                if (!isset($pair[1])) {
                    // @service
                    $val = new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference($pair[0]);
                } elseif (\preg_match('#^[A-Z][A-Z0-9_]*$#D', $pair[1], $m)) {
                    // @service::CONSTANT
                    $val = \_PhpScoper26e51eeacccf\Nette\DI\ContainerBuilder::literal($this->resolveReferenceType(new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference($pair[0])) . '::' . $pair[1]);
                } else {
                    // @service::property
                    $val = new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Statement([new \_PhpScoper26e51eeacccf\Nette\DI\Definitions\Reference($pair[0]), '$' . $pair[1]]);
                }
            } elseif (\is_string($val) && \substr($val, 0, 2) === '@@') {
                // escaped text @@
                $val = \substr($val, 1);
            }
        });
        return $arguments;
    }
    /**
     * Add missing arguments using autowiring.
     * @param  (callable(string $type, bool $single): object|object[]|null)  $getter
     * @throws ServiceCreationException
     */
    public static function autowireArguments(\ReflectionFunctionAbstract $method, array $arguments, callable $getter) : array
    {
        $optCount = 0;
        $num = -1;
        $res = [];
        foreach ($method->getParameters() as $num => $param) {
            $paramName = $param->name;
            if (!$param->isVariadic() && \array_key_exists($paramName, $arguments)) {
                $res[$num] = $arguments[$paramName];
                unset($arguments[$paramName], $arguments[$num]);
            } elseif (\array_key_exists($num, $arguments)) {
                $res[$num] = $arguments[$num];
                unset($arguments[$num]);
            } else {
                $res[$num] = self::autowireArgument($param, $getter);
            }
            $optCount = $param->isOptional() && $res[$num] === ($param->isDefaultValueAvailable() ? \_PhpScoper26e51eeacccf\Nette\Utils\Reflection::getParameterDefaultValue($param) : null) ? $optCount + 1 : 0;
        }
        // extra parameters
        while (\array_key_exists(++$num, $arguments)) {
            $res[$num] = $arguments[$num];
            unset($arguments[$num]);
            $optCount = 0;
        }
        if ($arguments) {
            throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException('Unable to pass specified arguments to ' . \_PhpScoper26e51eeacccf\Nette\Utils\Reflection::toString($method) . '.');
        } elseif ($optCount) {
            $res = \array_slice($res, 0, -$optCount);
        }
        return $res;
    }
    /**
     * Resolves missing argument using autowiring.
     * @param  (callable(string $type, bool $single): object|object[]|null)  $getter
     * @throws ServiceCreationException
     * @return mixed
     */
    private static function autowireArgument(\ReflectionParameter $parameter, callable $getter)
    {
        $type = \_PhpScoper26e51eeacccf\Nette\Utils\Reflection::getParameterType($parameter);
        $method = $parameter->getDeclaringFunction();
        $desc = \_PhpScoper26e51eeacccf\Nette\Utils\Reflection::toString($parameter);
        if ($type && !\_PhpScoper26e51eeacccf\Nette\Utils\Reflection::isBuiltinType($type)) {
            try {
                $res = $getter($type, \true);
            } catch (\_PhpScoper26e51eeacccf\Nette\DI\MissingServiceException $e) {
                $res = null;
            } catch (\_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException $e) {
                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("{$e->getMessage()} (needed by {$desc})", 0, $e);
            }
            if ($res !== null || $parameter->allowsNull()) {
                return $res;
            } elseif (\class_exists($type) || \interface_exists($type)) {
                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Service of type {$type} needed by {$desc} not found. Did you add it to configuration file?");
            } else {
                throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Class {$type} needed by {$desc} not found. Check type hint and 'use' statements.");
            }
        } elseif ($method instanceof \ReflectionMethod && $type === 'array' && \preg_match('#@param[ \\t]+([\\w\\\\]+)\\[\\][ \\t]+\\$' . $parameter->name . '#', (string) $method->getDocComment(), $m) && ($itemType = \_PhpScoper26e51eeacccf\Nette\Utils\Reflection::expandClassName($m[1], $method->getDeclaringClass())) && (\class_exists($itemType) || \interface_exists($itemType))) {
            return $getter($itemType, \false);
        } elseif ($type && $parameter->allowsNull() || $parameter->isOptional() || $parameter->isDefaultValueAvailable()) {
            // !optional + defaultAvailable = func($a = null, $b) since 5.4.7
            // optional + !defaultAvailable = i.e. Exception::__construct, mysqli::mysqli, ...
            return $parameter->isDefaultValueAvailable() ? \_PhpScoper26e51eeacccf\Nette\Utils\Reflection::getParameterDefaultValue($parameter) : null;
        } else {
            throw new \_PhpScoper26e51eeacccf\Nette\DI\ServiceCreationException("Parameter {$desc} has no class type hint or default value, so its value must be specified.");
        }
    }
}
