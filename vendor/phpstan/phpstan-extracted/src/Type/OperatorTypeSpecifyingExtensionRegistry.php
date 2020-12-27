<?php

declare (strict_types=1);
namespace PHPStan\Type;

use RectorPrefix20201227\PHPStan\Broker\Broker;
use RectorPrefix20201227\PHPStan\Reflection\BrokerAwareExtension;
class OperatorTypeSpecifyingExtensionRegistry
{
    /** @var OperatorTypeSpecifyingExtension[] */
    private $extensions;
    /**
     * @param \PHPStan\Type\OperatorTypeSpecifyingExtension[] $extensions
     */
    public function __construct(\RectorPrefix20201227\PHPStan\Broker\Broker $broker, array $extensions)
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof \RectorPrefix20201227\PHPStan\Reflection\BrokerAwareExtension) {
                continue;
            }
            $extension->setBroker($broker);
        }
        $this->extensions = $extensions;
    }
    /**
     * @return OperatorTypeSpecifyingExtension[]
     */
    public function getOperatorTypeSpecifyingExtensions(string $operator, \PHPStan\Type\Type $leftType, \PHPStan\Type\Type $rightType) : array
    {
        return \array_values(\array_filter($this->extensions, static function (\PHPStan\Type\OperatorTypeSpecifyingExtension $extension) use($operator, $leftType, $rightType) : bool {
            return $extension->isOperatorSupported($operator, $leftType, $rightType);
        }));
    }
}
