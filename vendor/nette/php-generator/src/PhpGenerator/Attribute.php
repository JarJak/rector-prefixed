<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScopera143bcca66cb\Nette\PhpGenerator;

use _PhpScopera143bcca66cb\Nette;
/**
 * PHP Attribute.
 */
final class Attribute
{
    use Nette\SmartObject;
    /** @var string */
    private $name;
    /** @var array */
    private $args;
    public function __construct(string $name, array $args)
    {
        if (!\_PhpScopera143bcca66cb\Nette\PhpGenerator\Helpers::isNamespaceIdentifier($name)) {
            throw new \_PhpScopera143bcca66cb\Nette\InvalidArgumentException("Value '{$name}' is not valid attribute name.");
        }
        $this->name = $name;
        $this->args = $args;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getArguments() : array
    {
        return $this->args;
    }
}
