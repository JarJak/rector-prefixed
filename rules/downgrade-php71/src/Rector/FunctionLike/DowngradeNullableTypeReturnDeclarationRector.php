<?php

declare (strict_types=1);
namespace Rector\DowngradePhp71\Rector\FunctionLike;

use PhpParser\Node\FunctionLike;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DowngradePhp71\Tests\Rector\FunctionLike\DowngradeNullableTypeReturnDeclarationRector\DowngradeNullableTypeReturnDeclarationRectorTest
 */
final class DowngradeNullableTypeReturnDeclarationRector extends \Rector\DowngradePhp71\Rector\FunctionLike\AbstractDowngradeReturnDeclarationRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove returning nullable types, add a @return tag instead', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
<?php

namespace _PhpScoper006a73f0e455;

class SomeClass
{
    public function getResponseOrNothing(bool $flag) : ?string
    {
        if ($flag) {
            return 'Hello world';
        }
        return null;
    }
}
\class_alias('_PhpScoper006a73f0e455\\SomeClass', 'SomeClass', \false);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
<?php

namespace _PhpScoper006a73f0e455;

class SomeClass
{
    /**
     * @return string|null
     */
    public function getResponseOrNothing(bool $flag)
    {
        if ($flag) {
            return 'Hello world';
        }
        return null;
    }
}
\class_alias('_PhpScoper006a73f0e455\\SomeClass', 'SomeClass', \false);
CODE_SAMPLE
, [self::ADD_DOC_BLOCK => \true])]);
    }
    /**
     * @param ClassMethod|Function_ $functionLike
     */
    public function shouldRemoveReturnDeclaration(\PhpParser\Node\FunctionLike $functionLike) : bool
    {
        if ($functionLike->returnType === null) {
            return \false;
        }
        // Check it is the union type
        return $functionLike->returnType instanceof \PhpParser\Node\NullableType;
    }
}