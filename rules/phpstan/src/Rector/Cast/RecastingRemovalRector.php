<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\PHPStan\Rector\Cast;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Array_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Bool_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Double;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Int_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Object_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\String_;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\BooleanType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\FloatType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\MixedType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\StringType;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\PHPStan\Tests\Rector\Cast\RecastingRemovalRector\RecastingRemovalRectorTest
 */
final class RecastingRemovalRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string[]
     */
    private const CAST_CLASS_TO_NODE_TYPE = [\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\String_::class => \_PhpScoper0a2ac50786fa\PHPStan\Type\StringType::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Bool_::class => \_PhpScoper0a2ac50786fa\PHPStan\Type\BooleanType::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Array_::class => \_PhpScoper0a2ac50786fa\PHPStan\Type\ArrayType::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Int_::class => \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Object_::class => \_PhpScoper0a2ac50786fa\PHPStan\Type\ObjectType::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast\Double::class => \_PhpScoper0a2ac50786fa\PHPStan\Type\FloatType::class];
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Removes recasting of the same type', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$string = '';
$string = (string) $string;

$array = [];
$array = (array) $array;
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$string = '';
$string = $string;

$array = [];
$array = $array;
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Cast::class];
    }
    /**
     * @param Cast $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        $nodeClass = \get_class($node);
        if (!isset(self::CAST_CLASS_TO_NODE_TYPE[$nodeClass])) {
            return null;
        }
        $nodeType = $this->getStaticType($node->expr);
        if ($nodeType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\MixedType) {
            return null;
        }
        $sameNodeType = self::CAST_CLASS_TO_NODE_TYPE[$nodeClass];
        if (!\is_a($nodeType, $sameNodeType, \true)) {
            return null;
        }
        return $node->expr;
    }
}
