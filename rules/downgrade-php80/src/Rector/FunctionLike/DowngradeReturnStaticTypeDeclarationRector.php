<?php

declare (strict_types=1);
namespace Rector\DowngradePhp80\Rector\FunctionLike;

use Rector\DowngradePhp72\Rector\FunctionLike\AbstractDowngradeReturnTypeDeclarationRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\DowngradePhp80\Tests\Rector\FunctionLike\DowngradeReturnStaticTypeDeclarationRector\DowngradeReturnStaticTypeDeclarationRectorTest
 */
final class DowngradeReturnStaticTypeDeclarationRector extends \Rector\DowngradePhp72\Rector\FunctionLike\AbstractDowngradeReturnTypeDeclarationRector
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition($this->getRectorDefinitionDescription(), [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
<?php

namespace _PhpScoper26e51eeacccf;

class SomeClass
{
    public function getStatic() : \_PhpScoper26e51eeacccf\static
    {
        return new static();
    }
}
\class_alias('_PhpScoper26e51eeacccf\\SomeClass', 'SomeClass', \false);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
<?php

namespace _PhpScoper26e51eeacccf;

class SomeClass
{
    /**
     * @return static
     */
    public function getStatic()
    {
        return new static();
    }
}
\class_alias('_PhpScoper26e51eeacccf\\SomeClass', 'SomeClass', \false);
CODE_SAMPLE
, [self::ADD_DOC_BLOCK => \true])]);
    }
    public function getTypeNameToRemove() : string
    {
        return 'static';
    }
}
