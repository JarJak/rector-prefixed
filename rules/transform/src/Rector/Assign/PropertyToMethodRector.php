<?php

declare (strict_types=1);
namespace Rector\Transform\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Transform\ValueObject\PropertyToMethod;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use RectorPrefix20210118\Webmozart\Assert\Assert;
/**
 * @see \Rector\Transform\Tests\Rector\Assign\PropertyToMethodRector\PropertyToMethodRectorTest
 */
final class PropertyToMethodRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const PROPERTIES_TO_METHOD_CALLS = 'properties_to_method_calls';
    /**
     * @var PropertyToMethod[]
     */
    private $propertiesToMethodCalls = [];
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        $firstConfiguration = [self::PROPERTIES_TO_METHOD_CALLS => [new \Rector\Transform\ValueObject\PropertyToMethod('SomeObject', 'property', 'getProperty', 'setProperty')]];
        $secondConfiguration = [self::PROPERTIES_TO_METHOD_CALLS => [new \Rector\Transform\ValueObject\PropertyToMethod('SomeObject', 'property', 'getConfig', null, ['someArg'])]];
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Replaces properties assign calls be defined methods.', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
$result = $object->property;
$object->property = $value;
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$result = $object->getProperty();
$object->setProperty($value);
CODE_SAMPLE
, $firstConfiguration), new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
$result = $object->property;
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$result = $object->getProperty('someArg');
CODE_SAMPLE
, $secondConfiguration)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\Assign::class];
    }
    /**
     * @param Assign $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($node->var instanceof \PhpParser\Node\Expr\PropertyFetch) {
            return $this->processSetter($node);
        }
        if ($node->expr instanceof \PhpParser\Node\Expr\PropertyFetch) {
            return $this->processGetter($node);
        }
        return null;
    }
    public function configure(array $configuration) : void
    {
        $propertiesToMethodCalls = $configuration[self::PROPERTIES_TO_METHOD_CALLS] ?? [];
        \RectorPrefix20210118\Webmozart\Assert\Assert::allIsInstanceOf($propertiesToMethodCalls, \Rector\Transform\ValueObject\PropertyToMethod::class);
        $this->propertiesToMethodCalls = $propertiesToMethodCalls;
    }
    private function processSetter(\PhpParser\Node\Expr\Assign $assign) : ?\PhpParser\Node
    {
        /** @var PropertyFetch $propertyFetchNode */
        $propertyFetchNode = $assign->var;
        $propertyToMethodCall = $this->matchPropertyFetchCandidate($propertyFetchNode);
        if ($propertyToMethodCall === null) {
            return null;
        }
        if ($propertyToMethodCall->getNewSetMethod() === null) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        $args = $this->createArgs([$assign->expr]);
        /** @var Variable $variable */
        $variable = $propertyFetchNode->var;
        return $this->createMethodCall($variable, $propertyToMethodCall->getNewSetMethod(), $args);
    }
    private function processGetter(\PhpParser\Node\Expr\Assign $assign) : ?\PhpParser\Node
    {
        /** @var PropertyFetch $propertyFetchNode */
        $propertyFetchNode = $assign->expr;
        $propertyToMethodCall = $this->matchPropertyFetchCandidate($propertyFetchNode);
        if ($propertyToMethodCall === null) {
            return null;
        }
        // simple method name
        if ($propertyToMethodCall->getNewGetMethod() !== '') {
            $assign->expr = $this->createMethodCall($propertyFetchNode->var, $propertyToMethodCall->getNewGetMethod());
            if ($propertyToMethodCall->getNewGetArguments() !== []) {
                $args = $this->createArgs($propertyToMethodCall->getNewGetArguments());
                $assign->expr->args = $args;
            }
            return $assign;
        }
        return $assign;
    }
    private function matchPropertyFetchCandidate(\PhpParser\Node\Expr\PropertyFetch $propertyFetch) : ?\Rector\Transform\ValueObject\PropertyToMethod
    {
        foreach ($this->propertiesToMethodCalls as $propertyToMethodCall) {
            if (!$this->isObjectType($propertyFetch->var, $propertyToMethodCall->getOldType())) {
                continue;
            }
            if (!$this->isName($propertyFetch, $propertyToMethodCall->getOldProperty())) {
                continue;
            }
            return $propertyToMethodCall;
        }
        return null;
    }
}
