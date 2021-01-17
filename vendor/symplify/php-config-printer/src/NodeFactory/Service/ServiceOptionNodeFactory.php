<?php

declare (strict_types=1);
namespace RectorPrefix20210117\Symplify\PhpConfigPrinter\NodeFactory\Service;

use RectorPrefix20210117\Nette\Utils\Strings;
use PhpParser\Node\Expr\MethodCall;
use RectorPrefix20210117\Symplify\PhpConfigPrinter\Contract\Converter\ServiceOptionsKeyYamlToPhpFactoryInterface;
use RectorPrefix20210117\Symplify\PhpConfigPrinter\ValueObject\YamlServiceKey;
final class ServiceOptionNodeFactory
{
    /**
     * @var ServiceOptionsKeyYamlToPhpFactoryInterface[]
     */
    private $serviceOptionKeyYamlToPhpFactories = [];
    /**
     * @param ServiceOptionsKeyYamlToPhpFactoryInterface[] $serviceOptionKeyYamlToPhpFactories
     */
    public function __construct(array $serviceOptionKeyYamlToPhpFactories)
    {
        $this->serviceOptionKeyYamlToPhpFactories = $serviceOptionKeyYamlToPhpFactories;
    }
    public function convertServiceOptionsToNodes(array $servicesValues, \PhpParser\Node\Expr\MethodCall $methodCall) : \PhpParser\Node\Expr\MethodCall
    {
        $servicesValues = $this->unNestArguments($servicesValues);
        foreach ($servicesValues as $key => $value) {
            // options started by decoration_<option> are used as options of the method decorate().
            if (\RectorPrefix20210117\Nette\Utils\Strings::startsWith($key, 'decoration_') || $key === 'alias') {
                continue;
            }
            foreach ($this->serviceOptionKeyYamlToPhpFactories as $serviceOptionKeyYamlToPhpFactory) {
                if (!$serviceOptionKeyYamlToPhpFactory->isMatch($key, $value)) {
                    continue;
                }
                $methodCall = $serviceOptionKeyYamlToPhpFactory->decorateServiceMethodCall($key, $value, $servicesValues, $methodCall);
                continue 2;
            }
        }
        return $methodCall;
    }
    private function isNestedArguments(array $servicesValues) : bool
    {
        if (\count($servicesValues) === 0) {
            return \false;
        }
        foreach (\array_keys($servicesValues) as $key) {
            if (!\RectorPrefix20210117\Nette\Utils\Strings::startsWith((string) $key, '$')) {
                return \false;
            }
        }
        return \true;
    }
    /**
     * @return array<string, mixed>
     */
    private function unNestArguments(array $servicesValues) : array
    {
        if (!$this->isNestedArguments($servicesValues)) {
            return $servicesValues;
        }
        return [\RectorPrefix20210117\Symplify\PhpConfigPrinter\ValueObject\YamlServiceKey::ARGUMENTS => $servicesValues];
    }
}
