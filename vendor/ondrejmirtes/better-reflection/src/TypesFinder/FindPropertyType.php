<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Roave\BetterReflection\TypesFinder;

use _PhpScoper006a73f0e455\phpDocumentor\Reflection\DocBlock\Tags\Var_;
use _PhpScoper006a73f0e455\phpDocumentor\Reflection\DocBlockFactory;
use _PhpScoper006a73f0e455\phpDocumentor\Reflection\Type;
use PhpParser\Node\Stmt\Namespace_;
use _PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionProperty;
use _PhpScoper006a73f0e455\Roave\BetterReflection\TypesFinder\PhpDocumentor\NamespaceNodeToReflectionTypeContext;
use function array_map;
use function array_merge;
use function explode;
class FindPropertyType
{
    /** @var ResolveTypes */
    private $resolveTypes;
    /** @var DocBlockFactory */
    private $docBlockFactory;
    /** @var NamespaceNodeToReflectionTypeContext */
    private $makeContext;
    public function __construct()
    {
        $this->resolveTypes = new \_PhpScoper006a73f0e455\Roave\BetterReflection\TypesFinder\ResolveTypes();
        $this->docBlockFactory = \_PhpScoper006a73f0e455\phpDocumentor\Reflection\DocBlockFactory::createInstance();
        $this->makeContext = new \_PhpScoper006a73f0e455\Roave\BetterReflection\TypesFinder\PhpDocumentor\NamespaceNodeToReflectionTypeContext();
    }
    /**
     * Given a property, attempt to find the type of the property.
     *
     * @return Type[]
     */
    public function __invoke(\_PhpScoper006a73f0e455\Roave\BetterReflection\Reflection\ReflectionProperty $reflectionProperty, ?\PhpParser\Node\Stmt\Namespace_ $namespace) : array
    {
        $docComment = $reflectionProperty->getDocComment();
        if ($docComment === '') {
            return [];
        }
        $context = $this->makeContext->__invoke($namespace);
        /** @var Var_[] $varTags */
        $varTags = $this->docBlockFactory->create($docComment, $context)->getTagsByName('var');
        return \array_merge([], ...\array_map(function (\_PhpScoper006a73f0e455\phpDocumentor\Reflection\DocBlock\Tags\Var_ $varTag) use($context) {
            return $this->resolveTypes->__invoke(\explode('|', (string) $varTag->getType()), $context);
        }, $varTags));
    }
}
