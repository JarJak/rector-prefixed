<?php

namespace _PhpScoper006a73f0e455;

#ifdef LIBXML_XPATH_ENABLED
class DOMXPath
{
    public function __construct(\DOMDocument $document, bool $registerNodeNS = \true)
    {
    }
    /** @return mixed */
    public function evaluate(string $expression, ?\DOMNode $contextNode = null, bool $registerNodeNS = \true)
    {
    }
    /** @return mixed */
    public function query(string $expression, ?\DOMNode $contextNode = null, bool $registerNodeNS = \true)
    {
    }
    /** @return bool */
    public function registerNamespace(string $prefix, string $namespace)
    {
    }
    /** @return void */
    public function registerPhpFunctions(string|array|null $restrict = null)
    {
    }
}
#ifdef LIBXML_XPATH_ENABLED
\class_alias('_PhpScoper006a73f0e455\\DOMXPath', 'DOMXPath', \false);
