<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\Manipulator;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Name\FullyQualified;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassConst;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\TraitUse;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Type;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\NodeFactory;
use _PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver;
final class ClassInsertManipulator
{
    /**
     * @var string[]
     */
    private const BEFORE_TRAIT_TYPES = [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\TraitUse::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod::class];
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var NodeFactory
     */
    private $nodeFactory;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\NodeFactory $nodeFactory, \_PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->nodeFactory = $nodeFactory;
    }
    /**
     * @param ClassMethod|Property|ClassConst|ClassMethod $stmt
     */
    public function addAsFirstMethod(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt $stmt) : void
    {
        if ($this->isSuccessToInsertBeforeFirstMethod($class, $stmt)) {
            return;
        }
        if ($this->isSuccessToInsertAfterLastProperty($class, $stmt)) {
            return;
        }
        $class->stmts[] = $stmt;
    }
    public function addConstantToClass(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, string $constantName, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassConst $classConst) : void
    {
        if ($this->hasClassConstant($class, $constantName)) {
            return;
        }
        $this->addAsFirstMethod($class, $classConst);
    }
    /**
     * @param Property[] $properties
     */
    public function addPropertiesToClass(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, array $properties) : void
    {
        foreach ($properties as $property) {
            $this->addAsFirstMethod($class, $property);
        }
    }
    public function addPropertyToClass(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, string $name, ?\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $type) : void
    {
        if ($this->hasClassProperty($class, $name)) {
            return;
        }
        $property = $this->nodeFactory->createPrivatePropertyFromNameAndType($name, $type);
        $this->addAsFirstMethod($class, $property);
    }
    public function addInjectPropertyToClass(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, string $name, ?\_PhpScoper0a2ac50786fa\PHPStan\Type\Type $type) : void
    {
        if ($this->hasClassProperty($class, $name)) {
            return;
        }
        $propertyNode = $this->nodeFactory->createPublicInjectPropertyFromNameAndType($name, $type);
        $this->addAsFirstMethod($class, $propertyNode);
    }
    public function addAsFirstTrait(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, string $traitName) : void
    {
        $traitUse = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\TraitUse([new \_PhpScoper0a2ac50786fa\PhpParser\Node\Name\FullyQualified($traitName)]);
        $this->addTraitUse($class, $traitUse);
    }
    /**
     * @param Stmt[] $nodes
     * @return Stmt[]
     */
    public function insertBefore(array $nodes, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt $stmt, int $key) : array
    {
        \array_splice($nodes, $key, 0, [$stmt]);
        return $nodes;
    }
    private function isSuccessToInsertBeforeFirstMethod(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt $stmt) : bool
    {
        foreach ($class->stmts as $key => $classStmt) {
            if (!$classStmt instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod) {
                continue;
            }
            $class->stmts = $this->insertBefore($class->stmts, $stmt, $key);
            return \true;
        }
        return \false;
    }
    private function isSuccessToInsertAfterLastProperty(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt $stmt) : bool
    {
        $previousElement = null;
        foreach ($class->stmts as $key => $classStmt) {
            if ($previousElement instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property && !$classStmt instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Property) {
                $class->stmts = $this->insertBefore($class->stmts, $stmt, $key);
                return \true;
            }
            $previousElement = $classStmt;
        }
        return \false;
    }
    private function hasClassConstant(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, string $constantName) : bool
    {
        foreach ($class->getConstants() as $classConst) {
            if ($this->nodeNameResolver->isName($classConst, $constantName)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * Waits on https://github.com/nikic/PHP-Parser/pull/646
     */
    private function hasClassProperty(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, string $name) : bool
    {
        foreach ($class->getProperties() as $property) {
            if (!$this->nodeNameResolver->isName($property, $name)) {
                continue;
            }
            return \true;
        }
        return \false;
    }
    private function addTraitUse(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\TraitUse $traitUse) : void
    {
        foreach (self::BEFORE_TRAIT_TYPES as $type) {
            foreach ($class->stmts as $key => $classStmt) {
                if (!$classStmt instanceof $type) {
                    continue;
                }
                $class->stmts = $this->insertBefore($class->stmts, $traitUse, $key);
                return;
            }
        }
        $class->stmts[] = $traitUse;
    }
}
