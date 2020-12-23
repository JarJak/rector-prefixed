<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Doctrine\Uuid;

use _PhpScoper0a2ac50786fa\Nette\Utils\Strings;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property;
use _PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScoper0a2ac50786fa\Rector\Doctrine\PhpDocParser\DoctrineDocBlockResolver;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
final class JoinTableNameResolver
{
    /**
     * @var DoctrineDocBlockResolver
     */
    private $doctrineDocBlockResolver;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\Doctrine\PhpDocParser\DoctrineDocBlockResolver $doctrineDocBlockResolver)
    {
        $this->doctrineDocBlockResolver = $doctrineDocBlockResolver;
    }
    /**
     * Create many-to-many table name like: "first_table_second_table_uuid"
     */
    public function resolveManyToManyUuidTableNameForProperty(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property $property) : string
    {
        /** @var string $className */
        $className = $property->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        $currentTableName = $this->resolveShortClassName($className);
        $targetEntity = $this->doctrineDocBlockResolver->getTargetEntity($property);
        if ($targetEntity === null) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        $targetTableName = $this->resolveShortClassName($targetEntity);
        return \strtolower($currentTableName . '_' . $targetTableName) . '_uuid';
    }
    private function resolveShortClassName(string $currentClass) : string
    {
        if (!\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::contains($currentClass, '\\')) {
            return $currentClass;
        }
        return (string) \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::after($currentClass, '\\', -1);
    }
}
