<?php

namespace _PhpScopera143bcca66cb;

class SimpleXMLElement implements \Stringable, \Countable, \RecursiveIterator
{
    /** @return array|false */
    public function xpath(string $expression)
    {
    }
    /** @return bool */
    public function registerXPathNamespace(string $prefix, string $namespace)
    {
    }
    /** @return string|bool */
    public function asXML(?string $filename = null)
    {
    }
    /**
     * @return string|bool
     * @alias SimpleXMLElement::asXML
     */
    public function saveXML(?string $filename = null)
    {
    }
    /** @return array */
    public function getNamespaces(bool $recursive = \false)
    {
    }
    /** @return array|false */
    public function getDocNamespaces(bool $recursive = \false, bool $fromRoot = \true)
    {
    }
    /** @return SimpleXMLIterator */
    public function children(?string $namespaceOrPrefix = null, bool $isPrefix = \false)
    {
    }
    /** @return SimpleXMLIterator */
    public function attributes(?string $namespaceOrPrefix = null, bool $isPrefix = \false)
    {
    }
    public function __construct(string $data, int $options = 0, bool $dataIsURL = \false, string $namespaceOrPrefix = "", bool $isPrefix = \false)
    {
    }
    /** @return SimpleXMLElement */
    public function addChild(string $qualifiedName, ?string $value = null, ?string $namespace = null)
    {
    }
    /** @return SimpleXMLElement */
    public function addAttribute(string $qualifiedName, ?string $value = null, ?string $namespace = null)
    {
    }
    /** @return string */
    public function getName()
    {
    }
    public function __toString() : string
    {
    }
    /** @return int */
    public function count()
    {
    }
    /** @return void */
    public function rewind()
    {
    }
    /** @return bool */
    public function valid()
    {
    }
    public function current()
    {
    }
    /** @return string|false */
    public function key()
    {
    }
    /** @return void */
    public function next()
    {
    }
    /** @return bool */
    public function hasChildren()
    {
    }
    /** @return SimpleXMLElement|null */
    public function getChildren()
    {
    }
}
\class_alias('_PhpScopera143bcca66cb\\SimpleXMLElement', 'SimpleXMLElement', \false);
