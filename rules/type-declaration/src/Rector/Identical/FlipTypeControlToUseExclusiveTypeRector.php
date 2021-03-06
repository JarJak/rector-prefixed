<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\Rector\Identical;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Expression;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareIdentifierTypeNode;
use Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareUnionTypeNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\TypeDeclaration\Tests\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector\FlipTypeControlToUseExclusiveTypeRectorTest
 */
final class FlipTypeControlToUseExclusiveTypeRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Flip type control to use exclusive type', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(array $values)
    {
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $functionLike->getAttribute(AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return;
        }
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(array $values)
    {
        $phpDocInfo = $functionLike->getAttribute(AttributeKey::PHP_DOC_INFO);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return;
        }
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
        return [\PhpParser\Node\Expr\BinaryOp\Identical::class];
    }
    /**
     * @param Identical $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isNull($node->left) && !$this->isNull($node->right)) {
            return null;
        }
        $variable = $this->isNull($node->left) ? $node->right : $node->left;
        $assign = $this->getVariableAssign($node, $variable);
        if (!$assign instanceof \PhpParser\Node\Expr\Assign) {
            return null;
        }
        $expression = $assign->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if (!$expression instanceof \PhpParser\Node\Stmt\Expression) {
            return null;
        }
        $phpDocInfo = $expression->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if (!$phpDocInfo instanceof \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo) {
            return null;
        }
        /** @var AttributeAwareIdentifierTypeNode[] $types */
        $types = $this->getTypes($phpDocInfo);
        if ($this->skipNotNullOneOf($types)) {
            return null;
        }
        return $this->processConvertToExclusiveType($types, $variable, $phpDocInfo);
    }
    /**
     * @param AttributeAwareIdentifierTypeNode[] $types
     */
    private function processConvertToExclusiveType(array $types, \PhpParser\Node\Expr $expr, \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo $phpDocInfo) : ?\PhpParser\Node\Expr\BooleanNot
    {
        $type = $types[0]->name === 'null' ? $types[1]->name : $types[0]->name;
        if (!\class_exists($type) && !\interface_exists($type)) {
            return null;
        }
        /** @var VarTagValueNode $tagValueNode */
        $tagValueNode = $phpDocInfo->getVarTagValueNode();
        $phpDocInfo->removeTagValueNodeFromNode($tagValueNode);
        return new \PhpParser\Node\Expr\BooleanNot(new \PhpParser\Node\Expr\Instanceof_($expr, new \PhpParser\Node\Name\FullyQualified($type)));
    }
    /**
     * @return TypeNode[]
     */
    private function getTypes(\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo $phpDocInfo) : array
    {
        $tagValueNode = $phpDocInfo->getVarTagValueNode();
        if (!$tagValueNode instanceof \PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode) {
            return [];
        }
        if (!$tagValueNode->type instanceof \Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareUnionTypeNode) {
            return [];
        }
        if (\count($tagValueNode->type->types) > 2) {
            return [];
        }
        return $tagValueNode->type->types;
    }
    private function getVariableAssign(\PhpParser\Node\Expr\BinaryOp\Identical $identical, \PhpParser\Node\Expr $expr) : ?\PhpParser\Node
    {
        return $this->betterNodeFinder->findFirstPrevious($identical, function (\PhpParser\Node $node) use($expr) : bool {
            if (!$node instanceof \PhpParser\Node\Expr\Assign) {
                return \false;
            }
            return $this->areNodesEqual($node->var, $expr);
        });
    }
    /**
     * @param AttributeAwareIdentifierTypeNode[] $types
     */
    private function skipNotNullOneOf(array $types) : bool
    {
        if ($types === []) {
            return \true;
        }
        foreach ($types as $type) {
            if (!$type instanceof \Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareIdentifierTypeNode) {
                return \true;
            }
        }
        if ($types[0]->name === $types[1]->name) {
            return \true;
        }
        if ($types[0]->name === 'null') {
            return \false;
        }
        return $types[1]->name !== 'null';
    }
}
