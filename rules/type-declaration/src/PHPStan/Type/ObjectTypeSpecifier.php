<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\PHPStan\Type;

use _PhpScoper006a73f0e455\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\ClassExistenceStaticHelper;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStan\Type\AliasedObjectType;
use Rector\PHPStan\Type\FullyQualifiedObjectType;
use Rector\PHPStan\Type\ShortenedObjectType;
final class ObjectTypeSpecifier
{
    /**
     * @return AliasedObjectType|FullyQualifiedObjectType|ObjectType|MixedType
     */
    public function narrowToFullyQualifiedOrAlaisedObjectType(\PhpParser\Node $node, \PHPStan\Type\ObjectType $objectType) : \PHPStan\Type\Type
    {
        /** @var Use_[]|null $uses */
        $uses = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::USE_NODES);
        if ($uses === null) {
            return $objectType;
        }
        $aliasedObjectType = $this->matchAliasedObjectType($node, $objectType);
        if ($aliasedObjectType !== null) {
            return $aliasedObjectType;
        }
        $shortenedObjectType = $this->matchShortenedObjectType($node, $objectType);
        if ($shortenedObjectType !== null) {
            return $shortenedObjectType;
        }
        $sameNamespacedObjectType = $this->matchSameNamespacedObjectType($node, $objectType);
        if ($sameNamespacedObjectType !== null) {
            return $sameNamespacedObjectType;
        }
        $className = \ltrim($objectType->getClassName(), '\\');
        if (\Rector\NodeTypeResolver\ClassExistenceStaticHelper::doesClassLikeExist($className)) {
            return new \Rector\PHPStan\Type\FullyQualifiedObjectType($className);
        }
        // invalid type
        return new \PHPStan\Type\MixedType();
    }
    private function matchAliasedObjectType(\PhpParser\Node $node, \PHPStan\Type\ObjectType $objectType) : ?\Rector\PHPStan\Type\AliasedObjectType
    {
        /** @var Use_[]|null $uses */
        $uses = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::USE_NODES);
        if ($uses === null) {
            return null;
        }
        foreach ($uses as $use) {
            foreach ($use->uses as $useUse) {
                if ($useUse->alias === null) {
                    continue;
                }
                $useName = $useUse->name->toString();
                $alias = $useUse->alias->toString();
                $fullyQualifiedName = $useUse->name->toString();
                // A. is alias in use statement matching this class alias
                if ($useUse->alias->toString() === $objectType->getClassName()) {
                    return new \Rector\PHPStan\Type\AliasedObjectType($alias, $fullyQualifiedName);
                }
                // B. is aliased classes matching the class name
                if ($useName === $objectType->getClassName()) {
                    return new \Rector\PHPStan\Type\AliasedObjectType($alias, $fullyQualifiedName);
                }
            }
        }
        return null;
    }
    private function matchShortenedObjectType(\PhpParser\Node $node, \PHPStan\Type\ObjectType $objectType) : ?\Rector\PHPStan\Type\ShortenedObjectType
    {
        /** @var Use_[]|null $uses */
        $uses = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::USE_NODES);
        if ($uses === null) {
            return null;
        }
        foreach ($uses as $use) {
            foreach ($use->uses as $useUse) {
                if ($useUse->alias !== null) {
                    continue;
                }
                $partialNamespaceObjectType = $this->matchPartialNamespaceObjectType($objectType, $useUse);
                if ($partialNamespaceObjectType !== null) {
                    return $partialNamespaceObjectType;
                }
                $partialNamespaceObjectType = $this->matchClassWithLastUseImportPart($objectType, $useUse);
                if ($partialNamespaceObjectType instanceof \Rector\PHPStan\Type\FullyQualifiedObjectType) {
                    return $partialNamespaceObjectType->getShortNameType();
                }
                if ($partialNamespaceObjectType instanceof \Rector\PHPStan\Type\ShortenedObjectType) {
                    return $partialNamespaceObjectType;
                }
            }
        }
        return null;
    }
    private function matchSameNamespacedObjectType(\PhpParser\Node $node, \PHPStan\Type\ObjectType $objectType) : ?\PHPStan\Type\ObjectType
    {
        $namespaceName = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NAMESPACE_NAME);
        if ($namespaceName === null) {
            return null;
        }
        $namespacedObject = $namespaceName . '\\' . $objectType->getClassName();
        if (\Rector\NodeTypeResolver\ClassExistenceStaticHelper::doesClassLikeExist($namespacedObject)) {
            return new \Rector\PHPStan\Type\FullyQualifiedObjectType($namespacedObject);
        }
        return null;
    }
    private function matchPartialNamespaceObjectType(\PHPStan\Type\ObjectType $objectType, \PhpParser\Node\Stmt\UseUse $useUse) : ?\Rector\PHPStan\Type\ShortenedObjectType
    {
        // partial namespace
        if (!\_PhpScoper006a73f0e455\Nette\Utils\Strings::startsWith($objectType->getClassName(), $useUse->name->getLast() . '\\')) {
            return null;
        }
        $classNameWithoutLastUsePart = \_PhpScoper006a73f0e455\Nette\Utils\Strings::after($objectType->getClassName(), '\\', 1);
        $connectedClassName = $useUse->name->toString() . '\\' . $classNameWithoutLastUsePart;
        if (!\Rector\NodeTypeResolver\ClassExistenceStaticHelper::doesClassLikeExist($connectedClassName)) {
            return null;
        }
        if ($objectType->getClassName() === $connectedClassName) {
            return null;
        }
        return new \Rector\PHPStan\Type\ShortenedObjectType($objectType->getClassName(), $connectedClassName);
    }
    /**
     * @return FullyQualifiedObjectType|ShortenedObjectType|null
     */
    private function matchClassWithLastUseImportPart(\PHPStan\Type\ObjectType $objectType, \PhpParser\Node\Stmt\UseUse $useUse) : ?\PHPStan\Type\ObjectType
    {
        if ($useUse->name->getLast() !== $objectType->getClassName()) {
            return null;
        }
        if (!\Rector\NodeTypeResolver\ClassExistenceStaticHelper::doesClassLikeExist($useUse->name->toString())) {
            return null;
        }
        if ($objectType->getClassName() === $useUse->name->toString()) {
            return new \Rector\PHPStan\Type\FullyQualifiedObjectType($objectType->getClassName());
        }
        return new \Rector\PHPStan\Type\ShortenedObjectType($objectType->getClassName(), $useUse->name->toString());
    }
}