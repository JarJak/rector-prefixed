<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\Value;

use _PhpScoper0a2ac50786fa\PhpParser\ConstExprEvaluationException;
use _PhpScoper0a2ac50786fa\PhpParser\ConstExprEvaluator;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Concat;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ClassConstFetch;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ConstFetch;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\MagicConst\Dir;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\MagicConst\File;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Constant\ConstantArrayType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ConstantScalarType;
use _PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScoper0a2ac50786fa\Rector\NodeCollector\NodeCollector\ParsedNodeCollector;
use _PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\NodeTypeResolver;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Rector\Core\Tests\PhpParser\Node\Value\ValueResolverTest
 */
final class ValueResolver
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var ConstExprEvaluator
     */
    private $constExprEvaluator;
    /**
     * @var ParsedNodeCollector
     */
    private $parsedNodeCollector;
    /**
     * @var NodeTypeResolver
     */
    private $nodeTypeResolver;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\NodeTypeResolver $nodeTypeResolver, \_PhpScoper0a2ac50786fa\Rector\NodeCollector\NodeCollector\ParsedNodeCollector $parsedNodeCollector)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->parsedNodeCollector = $parsedNodeCollector;
        $this->nodeTypeResolver = $nodeTypeResolver;
    }
    /**
     * @param mixed $value
     */
    public function isValue(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr $expr, $value) : bool
    {
        return $this->getValue($expr) === $value;
    }
    /**
     * @return mixed|null
     */
    public function getValue(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr $expr, bool $resolvedClassReference = \false)
    {
        if ($expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Concat) {
            return $this->processConcat($expr, $resolvedClassReference);
        }
        if ($expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ClassConstFetch && $resolvedClassReference) {
            $class = $this->nodeNameResolver->getName($expr->class);
            if (\in_array($class, ['self', 'static'], \true)) {
                return $expr->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
            }
            if ($this->nodeNameResolver->isName($expr->name, 'class')) {
                return $class;
            }
        }
        try {
            $constExprEvaluator = $this->getConstExprEvaluator();
            $value = $constExprEvaluator->evaluateDirectly($expr);
        } catch (\_PhpScoper0a2ac50786fa\PhpParser\ConstExprEvaluationException $constExprEvaluationException) {
            $value = null;
        }
        if ($value !== null) {
            return $value;
        }
        if ($expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ConstFetch) {
            return $this->nodeNameResolver->getName($expr);
        }
        $nodeStaticType = $this->nodeTypeResolver->getStaticType($expr);
        if ($nodeStaticType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\Constant\ConstantArrayType) {
            return $this->extractConstantArrayTypeValue($nodeStaticType);
        }
        if ($nodeStaticType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\ConstantScalarType) {
            return $nodeStaticType->getValue();
        }
        return null;
    }
    private function processConcat(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\BinaryOp\Concat $concat, bool $resolvedClassReference) : string
    {
        return $this->getValue($concat->left, $resolvedClassReference) . $this->getValue($concat->right, $resolvedClassReference);
    }
    private function getConstExprEvaluator() : \_PhpScoper0a2ac50786fa\PhpParser\ConstExprEvaluator
    {
        if ($this->constExprEvaluator !== null) {
            return $this->constExprEvaluator;
        }
        $this->constExprEvaluator = new \_PhpScoper0a2ac50786fa\PhpParser\ConstExprEvaluator(function (\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr $expr) {
            if ($expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\MagicConst\Dir) {
                // __DIR__
                return $this->resolveDirConstant($expr);
            }
            if ($expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\MagicConst\File) {
                // __FILE__
                return $this->resolveFileConstant($expr);
            }
            // resolve "SomeClass::SOME_CONST"
            if ($expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ClassConstFetch) {
                return $this->resolveClassConstFetch($expr);
            }
            throw new \_PhpScoper0a2ac50786fa\PhpParser\ConstExprEvaluationException(\sprintf('Expression of type "%s" cannot be evaluated', $expr->getType()));
        });
        return $this->constExprEvaluator;
    }
    /**
     * @return mixed[]
     */
    private function extractConstantArrayTypeValue(\_PhpScoper0a2ac50786fa\PHPStan\Type\Constant\ConstantArrayType $constantArrayType) : array
    {
        $keys = [];
        foreach ($constantArrayType->getKeyTypes() as $i => $keyType) {
            /** @var ConstantScalarType $keyType */
            $keys[$i] = $keyType->getValue();
        }
        $values = [];
        foreach ($constantArrayType->getValueTypes() as $i => $valueType) {
            if ($valueType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\Constant\ConstantArrayType) {
                $value = $this->extractConstantArrayTypeValue($valueType);
            } elseif ($valueType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\ConstantScalarType) {
                $value = $valueType->getValue();
            } else {
                // not sure about value
                continue;
            }
            $values[$keys[$i]] = $value;
        }
        return $values;
    }
    private function resolveDirConstant(\_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\MagicConst\Dir $dir) : string
    {
        $fileInfo = $dir->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::FILE_INFO);
        if (!$fileInfo instanceof \_PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        return $fileInfo->getPath();
    }
    private function resolveFileConstant(\_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\MagicConst\File $file) : string
    {
        $fileInfo = $file->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::FILE_INFO);
        if (!$fileInfo instanceof \_PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        return $fileInfo->getPathname();
    }
    /**
     * @return string|mixed
     */
    private function resolveClassConstFetch(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ClassConstFetch $classConstFetch)
    {
        $class = $this->nodeNameResolver->getName($classConstFetch->class);
        $constant = $this->nodeNameResolver->getName($classConstFetch->name);
        if ($class === null) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        if ($constant === null) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        if ($class === 'self') {
            $class = (string) $classConstFetch->class->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        }
        if ($constant === 'class') {
            return $class;
        }
        $classConstNode = $this->parsedNodeCollector->findClassConstant($class, $constant);
        if ($classConstNode === null) {
            // fallback to the name
            return $class . '::' . $constant;
        }
        return $this->constExprEvaluator->evaluateDirectly($classConstNode->consts[0]->value);
    }
}
