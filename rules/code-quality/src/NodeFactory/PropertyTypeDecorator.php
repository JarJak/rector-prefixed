<?php

declare (strict_types=1);
namespace Rector\CodeQuality\NodeFactory;

use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\StaticTypeMapper;
final class PropertyTypeDecorator
{
    /**
     * @var PhpVersionProvider
     */
    private $phpVersionProvider;
    /**
     * @var StaticTypeMapper
     */
    private $staticTypeMapper;
    public function __construct(\Rector\Core\Php\PhpVersionProvider $phpVersionProvider, \Rector\StaticTypeMapper\StaticTypeMapper $staticTypeMapper)
    {
        $this->phpVersionProvider = $phpVersionProvider;
        $this->staticTypeMapper = $staticTypeMapper;
    }
    public function decorateProperty(\PhpParser\Node\Stmt\Property $property, \PHPStan\Type\Type $propertyType) : void
    {
        $this->decoratePropertyWithVarDoc($property, $propertyType);
        $this->decoratePropertyWithType($property, $propertyType);
    }
    private function decoratePropertyWithVarDoc(\PhpParser\Node\Stmt\Property $property, \PHPStan\Type\Type $propertyType) : void
    {
        /** @var PhpDocInfo $phpDocInfo */
        $phpDocInfo = $property->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($this->isNonMixedArrayType($propertyType)) {
            $phpDocInfo->changeVarType($propertyType);
            $property->type = new \PhpParser\Node\Identifier('array');
            return;
        }
        if ($this->phpVersionProvider->isAtLeastPhpVersion(\Rector\Core\ValueObject\PhpVersionFeature::TYPED_PROPERTIES)) {
            $phpParserNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($propertyType);
            if ($phpParserNode === null) {
                // fallback to doc type in PHP 7.4
                $phpDocInfo->changeVarType($propertyType);
            }
        } else {
            $phpDocInfo->changeVarType($propertyType);
        }
    }
    private function decoratePropertyWithType(\PhpParser\Node\Stmt\Property $property, \PHPStan\Type\Type $propertyType) : void
    {
        if (!$this->phpVersionProvider->isAtLeastPhpVersion(\Rector\Core\ValueObject\PhpVersionFeature::TYPED_PROPERTIES)) {
            return;
        }
        $phpParserNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($propertyType);
        if ($phpParserNode === null) {
            return;
        }
        $property->type = $phpParserNode;
    }
    private function isNonMixedArrayType(\PHPStan\Type\Type $type) : bool
    {
        if (!$type instanceof \PHPStan\Type\ArrayType) {
            return \false;
        }
        if ($type->getKeyType() instanceof \PHPStan\Type\MixedType) {
            return \false;
        }
        return !$type->getItemType() instanceof \PHPStan\Type\MixedType;
    }
}