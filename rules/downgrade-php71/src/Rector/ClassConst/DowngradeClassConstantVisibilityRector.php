<?php

declare (strict_types=1);
namespace Rector\DowngradePhp71\Rector\ClassConst;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use Rector\DowngradePhp71\Rector\FunctionLike\AbstractDowngradeRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DowngradePhp71\Tests\Rector\ClassConst\DowngradeClassConstantVisibilityRectorTest
 */
final class DowngradeClassConstantVisibilityRector extends \Rector\DowngradePhp71\Rector\FunctionLike\AbstractDowngradeRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Downgrade class constant visibility', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
<?php

namespace _PhpScoper006a73f0e455;

class SomeClass
{
    public const PUBLIC_CONST_B = 2;
    protected const PROTECTED_CONST = 3;
    private const PRIVATE_CONST = 4;
}
\class_alias('_PhpScoper006a73f0e455\\SomeClass', 'SomeClass', \false);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
<?php

namespace _PhpScoper006a73f0e455;

class SomeClass
{
    const PUBLIC_CONST_B = 2;
    const PROTECTED_CONST = 3;
    const PRIVATE_CONST = 4;
}
\class_alias('_PhpScoper006a73f0e455\\SomeClass', 'SomeClass', \false);
CODE_SAMPLE
, [])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassConst::class];
    }
    /**
     * @param ClassConst $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $this->removeVisibility($node);
        return $node;
    }
}