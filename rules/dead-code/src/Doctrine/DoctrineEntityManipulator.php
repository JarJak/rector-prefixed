<?php

declare (strict_types=1);
namespace Rector\DeadCode\Doctrine;

use Doctrine\ORM\Mapping\Entity;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\Contract\Doctrine\DoctrineRelationTagValueNodeInterface;
use Rector\BetterPhpDocParser\Contract\Doctrine\InversedByNodeInterface;
use Rector\BetterPhpDocParser\Contract\Doctrine\MappedByNodeInterface;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\Class_\EntityTagValueNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\Class_\InheritanceTypeTagValueNode;
use Rector\Doctrine\PhpDocParser\DoctrineDocBlockResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
final class DoctrineEntityManipulator
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var DoctrineDocBlockResolver
     */
    private $doctrineDocBlockResolver;
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    public function __construct(\Rector\Doctrine\PhpDocParser\DoctrineDocBlockResolver $doctrineDocBlockResolver, \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->doctrineDocBlockResolver = $doctrineDocBlockResolver;
        $this->nodeTypeResolver = $nodeTypeResolver;
    }
    public function resolveOtherProperty(\PhpParser\Node\Stmt\Property $property) : ?string
    {
        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        $relationTagValueNode = $phpDocInfo->getByType(\Rector\BetterPhpDocParser\Contract\Doctrine\DoctrineRelationTagValueNodeInterface::class);
        if ($relationTagValueNode === null) {
            return null;
        }
        $otherProperty = null;
        if ($relationTagValueNode instanceof \Rector\BetterPhpDocParser\Contract\Doctrine\MappedByNodeInterface) {
            $otherProperty = $relationTagValueNode->getMappedBy();
        }
        if ($otherProperty !== null) {
            return $otherProperty;
        }
        if ($relationTagValueNode instanceof \Rector\BetterPhpDocParser\Contract\Doctrine\InversedByNodeInterface) {
            return $relationTagValueNode->getInversedBy();
        }
        return null;
    }
    public function isNonAbstractDoctrineEntityClass(\PhpParser\Node\Stmt\Class_ $class) : bool
    {
        if ($class->isAnonymous()) {
            return \false;
        }
        if ($class->isAbstract()) {
            return \false;
        }
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $class->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return \false;
        }
        // is parent entity
        if ($phpDocInfo->hasByType(\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\Class_\InheritanceTypeTagValueNode::class)) {
            return \false;
        }
        return $phpDocInfo->hasByType(\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\Class_\EntityTagValueNode::class);
    }
    public function removeMappedByOrInversedByFromProperty(\PhpParser\Node\Stmt\Property $property) : void
    {
        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        $relationTagValueNode = $phpDocInfo->getByType(\Rector\BetterPhpDocParser\Contract\Doctrine\DoctrineRelationTagValueNodeInterface::class);
        $shouldUpdate = \false;
        if ($relationTagValueNode instanceof \Rector\BetterPhpDocParser\Contract\Doctrine\MappedByNodeInterface && $relationTagValueNode->getMappedBy()) {
            $shouldUpdate = \true;
            $relationTagValueNode->removeMappedBy();
        }
        if ($relationTagValueNode instanceof \Rector\BetterPhpDocParser\Contract\Doctrine\InversedByNodeInterface && $relationTagValueNode->getInversedBy()) {
            $shouldUpdate = \true;
            $relationTagValueNode->removeInversedBy();
        }
        if (!$shouldUpdate) {
            return;
        }
    }
    public function isMethodCallOnDoctrineEntity(\PhpParser\Node $node, string $methodName) : bool
    {
        if (!$node instanceof \PhpParser\Node\Expr\MethodCall) {
            return \false;
        }
        if (!$this->nodeNameResolver->isName($node->name, $methodName)) {
            return \false;
        }
        $objectType = $this->nodeTypeResolver->resolve($node->var);
        if (!$objectType instanceof \PHPStan\Type\ObjectType) {
            return \false;
        }
        return $this->doctrineDocBlockResolver->isDoctrineEntityClass($objectType->getClassName());
    }
}
