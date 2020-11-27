<?php

declare (strict_types=1);
namespace Rector\Naming\Rector\Variable;

use _PhpScopera143bcca66cb\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\Core\Php\ReservedKeywordAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Util\StaticRectorStrings;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Naming\Tests\Rector\Variable\UnderscoreToCamelCaseLocalVariableNameRector\UnderscoreToCamelCaseLocalVariableNameRectorTest
 */
final class UnderscoreToCamelCaseLocalVariableNameRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var ReservedKeywordAnalyzer
     */
    private $reservedKeywordAnalyzer;
    public function __construct(\Rector\Core\Php\ReservedKeywordAnalyzer $reservedKeywordAnalyzer)
    {
        $this->reservedKeywordAnalyzer = $reservedKeywordAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change under_score local variable names to camelCase', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($a_b)
    {
        $some_value = $a_b;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($a_b)
    {
        $someValue = $a_b;
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\Variable::class];
    }
    /**
     * @param Variable $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $nodeName = $this->getName($node);
        if ($nodeName === null) {
            return null;
        }
        if (!\_PhpScopera143bcca66cb\Nette\Utils\Strings::contains($nodeName, '_')) {
            return null;
        }
        if ($this->reservedKeywordAnalyzer->isNativeVariable($nodeName)) {
            return null;
        }
        $camelCaseName = \Rector\Core\Util\StaticRectorStrings::underscoreToCamelCase($nodeName);
        if ($camelCaseName === 'this' || $camelCaseName === '' || \is_numeric($camelCaseName[0])) {
            return null;
        }
        $parentNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($parentNode instanceof \PhpParser\Node\Expr && $this->isFoundInParentNode($node)) {
            return null;
        }
        if (($parentNode instanceof \PhpParser\Node\Arg || $parentNode instanceof \PhpParser\Node\Param || $parentNode instanceof \PhpParser\Node\Stmt) && $this->isFoundInParentNode($node)) {
            return null;
        }
        if ($this->isFoundInPreviousNode($node)) {
            return null;
        }
        $node->name = $camelCaseName;
        return $node;
    }
    private function isFoundInParentNode(\PhpParser\Node\Expr\Variable $variable) : bool
    {
        $parentNode = $variable->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        while ($parentNode) {
            /** @var ClassMethod|Function_ $parentNode */
            $parentNode = $parentNode->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($parentNode instanceof \PhpParser\Node\Stmt\ClassMethod || $parentNode instanceof \PhpParser\Node\Stmt\Function_) {
                break;
            }
        }
        if ($parentNode === null) {
            return \false;
        }
        $params = $parentNode->getParams();
        foreach ($params as $param) {
            if ($param->var->name === $variable->name) {
                return \true;
            }
        }
        return \false;
    }
    private function isFoundInPreviousNode(\PhpParser\Node\Expr\Variable $variable) : bool
    {
        $previousNode = $variable->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PREVIOUS_NODE);
        return $previousNode instanceof \PhpParser\Node\Expr && $this->isFoundInParentNode($variable);
    }
}
