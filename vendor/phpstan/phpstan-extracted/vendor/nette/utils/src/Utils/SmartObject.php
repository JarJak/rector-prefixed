<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette;

use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers;
/**
 * Strict class for better experience.
 * - 'did you mean' hints
 * - access to undeclared members throws exceptions
 * - support for @property annotations
 * - support for calling event handlers stored in $onEvent via onEvent()
 */
trait SmartObject
{
    /**
     * @throws MemberAccessException
     */
    public function __call(string $name, array $args)
    {
        $class = \get_class($this);
        if (\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::hasProperty($class, $name) === 'event') {
            // calling event handlers
            $handlers = $this->{$name} ?? null;
            if (\is_iterable($handlers)) {
                foreach ($handlers as $handler) {
                    $handler(...$args);
                }
            } elseif ($handlers !== null) {
                throw new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\UnexpectedValueException("Property {$class}::\${$name} must be iterable or null, " . \gettype($handlers) . ' given.');
            }
        } else {
            \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::strictCall($class, $name);
        }
    }
    /**
     * @throws MemberAccessException
     */
    public static function __callStatic(string $name, array $args)
    {
        \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::strictStaticCall(static::class, $name);
    }
    /**
     * @return mixed
     * @throws MemberAccessException if the property is not defined.
     */
    public function &__get(string $name)
    {
        $class = \get_class($this);
        if ($prop = \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::getMagicProperties($class)[$name] ?? null) {
            // property getter
            if (!($prop & 0b1)) {
                throw new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\MemberAccessException("Cannot read a write-only property {$class}::\${$name}.");
            }
            $m = ($prop & 0b10 ? 'get' : 'is') . $name;
            if ($prop & 0b100) {
                // return by reference
                return $this->{$m}();
            } else {
                $val = $this->{$m}();
                return $val;
            }
        } else {
            \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::strictGet($class, $name);
        }
    }
    /**
     * @param  mixed  $value
     * @return void
     * @throws MemberAccessException if the property is not defined or is read-only
     */
    public function __set(string $name, $value)
    {
        $class = \get_class($this);
        if (\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::hasProperty($class, $name)) {
            // unsetted property
            $this->{$name} = $value;
        } elseif ($prop = \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::getMagicProperties($class)[$name] ?? null) {
            // property setter
            if (!($prop & 0b1000)) {
                throw new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\MemberAccessException("Cannot write to a read-only property {$class}::\${$name}.");
            }
            $this->{'set' . $name}($value);
        } else {
            \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::strictSet($class, $name);
        }
    }
    /**
     * @return void
     * @throws MemberAccessException
     */
    public function __unset(string $name)
    {
        $class = \get_class($this);
        if (!\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::hasProperty($class, $name)) {
            throw new \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\MemberAccessException("Cannot unset the property {$class}::\${$name}.");
        }
    }
    public function __isset(string $name) : bool
    {
        return isset(\_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Nette\Utils\ObjectHelpers::getMagicProperties(\get_class($this))[$name]);
    }
}