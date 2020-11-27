<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */
declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Nette\PhpGenerator\Traits;

use _PhpScoper006a73f0e455\Nette\PhpGenerator\Attribute;
/**
 * @internal
 */
trait AttributeAware
{
    /** @var Attribute[] */
    private $attributes = [];
    /** @return static */
    public function addAttribute(string $name, array $args = []) : self
    {
        $this->attributes[] = new \_PhpScoper006a73f0e455\Nette\PhpGenerator\Attribute($name, $args);
        return $this;
    }
    /**
     * @param  Attribute[]  $attrs
     * @return static
     */
    public function setAttributes(array $attrs) : self
    {
        (function (\_PhpScoper006a73f0e455\Nette\PhpGenerator\Attribute ...$attrs) {
        })(...$attrs);
        $this->attributes = $attrs;
        return $this;
    }
    /** @return Attribute[] */
    public function getAttributes() : array
    {
        return $this->attributes;
    }
}