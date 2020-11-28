<?php

declare (strict_types=1);
namespace Symplify\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\Fixture\PHPCSFixer\Configurable;

use _PhpScoperabd03f0baf05\PhpCsFixer\AbstractFixer;
use _PhpScoperabd03f0baf05\PhpCsFixer\Tokenizer\Tokens;
use Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
final class SomeConfiguredFixer extends \_PhpScoperabd03f0baf05\PhpCsFixer\AbstractFixer implements \Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface, \Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface
{
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Some description', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
bad code
CODE_SAMPLE
, <<<'CODE_SAMPLE'
good code
CODE_SAMPLE
, ['key' => 'value'])]);
    }
    protected function applyFix(\SplFileInfo $file, \_PhpScoperabd03f0baf05\PhpCsFixer\Tokenizer\Tokens $tokens)
    {
    }
    public function getDefinition()
    {
    }
    public function isCandidate(\_PhpScoperabd03f0baf05\PhpCsFixer\Tokenizer\Tokens $tokens)
    {
    }
}
