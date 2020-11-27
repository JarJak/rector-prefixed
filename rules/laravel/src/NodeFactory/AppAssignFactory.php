<?php

declare (strict_types=1);
namespace Rector\Laravel\NodeFactory;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareFullyQualifiedIdentifierTypeNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Laravel\ValueObject\ServiceNameTypeAndVariableName;
final class AppAssignFactory
{
    /**
     * @var PhpDocInfoFactory
     */
    private $phpDocInfoFactory;
    public function __construct(\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory $phpDocInfoFactory)
    {
        $this->phpDocInfoFactory = $phpDocInfoFactory;
    }
    public function createAssignExpression(\Rector\Laravel\ValueObject\ServiceNameTypeAndVariableName $serviceNameTypeAndVariableName, \PhpParser\Node\Expr $expr) : \PhpParser\Node\Stmt\Expression
    {
        $variable = new \PhpParser\Node\Expr\Variable($serviceNameTypeAndVariableName->getVariableName());
        $assign = new \PhpParser\Node\Expr\Assign($variable, $expr);
        $expression = new \PhpParser\Node\Stmt\Expression($assign);
        $this->decorateWithVarAnnotation($expression, $serviceNameTypeAndVariableName);
        return $expression;
    }
    private function decorateWithVarAnnotation(\PhpParser\Node\Stmt\Expression $expression, \Rector\Laravel\ValueObject\ServiceNameTypeAndVariableName $serviceNameTypeAndVariableName) : void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createEmpty($expression);
        $attributeAwareFullyQualifiedIdentifierTypeNode = new \Rector\AttributeAwarePhpDoc\Ast\Type\AttributeAwareFullyQualifiedIdentifierTypeNode($serviceNameTypeAndVariableName->getType());
        $varTagValueNode = new \PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode($attributeAwareFullyQualifiedIdentifierTypeNode, '$' . $serviceNameTypeAndVariableName->getVariableName(), '');
        $phpDocInfo->addTagValueNode($varTagValueNode);
        $phpDocInfo->makeSingleLined();
    }
}
