<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\Sensio\Bundle\FrameworkExtraBundle\Configuration;

if (\class_exists('_PhpScoper26e51eeacccf\\Sensio\\Bundle\\FrameworkExtraBundle\\Configuration\\Method')) {
    return;
}
/**
 * @Annotation
 */
class Method extends \_PhpScoper26e51eeacccf\Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation
{
    /**
     * An array of restricted HTTP methods.
     *
     * @var array
     */
    public $methods = [];
    /**
     * Returns the array of HTTP methods.
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }
    /**
     * Sets the HTTP methods.
     *
     * @param array|string $methods An HTTP method or an array of HTTP methods
     */
    public function setMethods($methods)
    {
        $this->methods = \is_array($methods) ? $methods : [$methods];
    }
    /**
     * Sets the HTTP methods.
     *
     * @param array|string $methods An HTTP method or an array of HTTP methods
     */
    public function setValue($methods)
    {
        $this->setMethods($methods);
    }
    /**
     * Returns the annotation alias name.
     *
     * @return string
     *
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'method';
    }
    /**
     * Only one method directive is allowed.
     *
     * @return bool
     *
     * @see ConfigurationInterface
     */
    public function allowArray()
    {
        return \false;
    }
}
