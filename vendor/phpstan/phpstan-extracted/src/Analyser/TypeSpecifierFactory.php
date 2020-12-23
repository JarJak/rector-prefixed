<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Analyser;

use _PhpScoper0a2ac50786fa\PhpParser\PrettyPrinter\Standard;
use _PhpScoper0a2ac50786fa\PHPStan\Broker\BrokerFactory;
use _PhpScoper0a2ac50786fa\PHPStan\DependencyInjection\Container;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\ReflectionProvider;
class TypeSpecifierFactory
{
    public const FUNCTION_TYPE_SPECIFYING_EXTENSION_TAG = 'phpstan.typeSpecifier.functionTypeSpecifyingExtension';
    public const METHOD_TYPE_SPECIFYING_EXTENSION_TAG = 'phpstan.typeSpecifier.methodTypeSpecifyingExtension';
    public const STATIC_METHOD_TYPE_SPECIFYING_EXTENSION_TAG = 'phpstan.typeSpecifier.staticMethodTypeSpecifyingExtension';
    /** @var \PHPStan\DependencyInjection\Container */
    private $container;
    public function __construct(\_PhpScoper0a2ac50786fa\PHPStan\DependencyInjection\Container $container)
    {
        $this->container = $container;
    }
    public function create() : \_PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifier
    {
        $typeSpecifier = new \_PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifier($this->container->getByType(\_PhpScoper0a2ac50786fa\PhpParser\PrettyPrinter\Standard::class), $this->container->getByType(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\ReflectionProvider::class), $this->container->getServicesByTag(self::FUNCTION_TYPE_SPECIFYING_EXTENSION_TAG), $this->container->getServicesByTag(self::METHOD_TYPE_SPECIFYING_EXTENSION_TAG), $this->container->getServicesByTag(self::STATIC_METHOD_TYPE_SPECIFYING_EXTENSION_TAG));
        foreach (\array_merge($this->container->getServicesByTag(\_PhpScoper0a2ac50786fa\PHPStan\Broker\BrokerFactory::PROPERTIES_CLASS_REFLECTION_EXTENSION_TAG), $this->container->getServicesByTag(\_PhpScoper0a2ac50786fa\PHPStan\Broker\BrokerFactory::METHODS_CLASS_REFLECTION_EXTENSION_TAG), $this->container->getServicesByTag(\_PhpScoper0a2ac50786fa\PHPStan\Broker\BrokerFactory::DYNAMIC_METHOD_RETURN_TYPE_EXTENSION_TAG), $this->container->getServicesByTag(\_PhpScoper0a2ac50786fa\PHPStan\Broker\BrokerFactory::DYNAMIC_STATIC_METHOD_RETURN_TYPE_EXTENSION_TAG), $this->container->getServicesByTag(\_PhpScoper0a2ac50786fa\PHPStan\Broker\BrokerFactory::DYNAMIC_FUNCTION_RETURN_TYPE_EXTENSION_TAG)) as $extension) {
            if (!$extension instanceof \_PhpScoper0a2ac50786fa\PHPStan\Analyser\TypeSpecifierAwareExtension) {
                continue;
            }
            $extension->setTypeSpecifier($typeSpecifier);
        }
        return $typeSpecifier;
    }
}
