<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\PHPStan\Rector\Assign;

use _PhpScoper0a2ac50786fa\Nette\Utils\Strings;
use _PhpScoper0a2ac50786fa\PhpParser\Comment\Doc;
use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Expression;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Nop;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/shopsys/shopsys/pull/524
 * @see \Rector\PHPStan\Tests\Rector\Assign\PHPStormVarAnnotationRector\PHPStormVarAnnotationRectorTest
 */
final class PHPStormVarAnnotationRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/YY5stJ/1
     */
    private const SINGLE_ASTERISK_COMMENT_START_REGEX = '#^\\/\\* #';
    /**
     * @var string
     * @see https://regex101.com/r/meD7rP/1
     */
    private const VAR_ANNOTATION_REGEX = '#\\@var(\\s)+\\$#';
    /**
     * @var string
     * @see https://regex101.com/r/yz2AZ7/1
     */
    private const VARIABLE_NAME_AND_TYPE_MATCH_REGEX = '#(?<variableName>\\$\\w+)(?<space>\\s+)(?<type>[\\\\\\w]+)#';
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change various @var annotation formats to one PHPStorm understands', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$config = 5;
/** @var \Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterConfig $config */
CODE_SAMPLE
, <<<'CODE_SAMPLE'
/** @var \Shopsys\FrameworkBundle\Model\Product\Filter\ProductFilterConfig $config */
$config = 5;
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign::class];
    }
    /**
     * @param Assign $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        /** @var Expression|null $expression */
        $expression = $node->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CURRENT_STATEMENT);
        // unable to analyze
        if ($expression === null) {
            return null;
        }
        /** @var Node|null $nextNode */
        $nextNode = $expression->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
        if ($nextNode === null) {
            return null;
        }
        $docContent = $this->getDocContent($nextNode);
        if ($docContent === '') {
            return null;
        }
        if (!\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::contains($docContent, '@var')) {
            return null;
        }
        if (!$node->var instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Variable) {
            return null;
        }
        $varName = '$' . $this->getName($node->var);
        $varPattern = '# ' . \preg_quote($varName, '#') . ' #';
        if (!\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::match($docContent, $varPattern)) {
            return null;
        }
        // switch docs
        $expression->setDocComment($this->createDocComment($nextNode));
        $expressionPhpDocInfo = $this->phpDocInfoFactory->createFromNode($expression);
        $expression->setAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO, $expressionPhpDocInfo);
        // invoke override
        $expression->setAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE, null);
        // remove otherwise empty node
        if ($nextNode instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Nop) {
            $this->removeNode($nextNode);
            return null;
        }
        // remove commnets
        $nextNode->setAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO, null);
        $nextNode->setAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::COMMENTS, null);
        return $node;
    }
    private function getDocContent(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : string
    {
        $docComment = $node->getDocComment();
        if ($docComment !== null) {
            return $docComment->getText();
        }
        if ($node->getComments() !== []) {
            $docContent = '';
            foreach ($node->getComments() as $comment) {
                $docContent .= $comment->getText();
            }
            return $docContent;
        }
        return '';
    }
    private function createDocComment(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : \_PhpScoper0a2ac50786fa\PhpParser\Comment\Doc
    {
        if ($node->getDocComment() !== null) {
            return $node->getDocComment();
        }
        $docContent = $this->getDocContent($node);
        // normalize content
        // starts with "/*", instead of "/**"
        if (\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::startsWith($docContent, '/* ')) {
            $docContent = \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::replace($docContent, self::SINGLE_ASTERISK_COMMENT_START_REGEX, '/** ');
        }
        // $value is first, instead of type is first
        if (\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::match($docContent, self::VAR_ANNOTATION_REGEX)) {
            $docContent = \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::replace($docContent, self::VARIABLE_NAME_AND_TYPE_MATCH_REGEX, '$3$2$1');
        }
        return new \_PhpScoper0a2ac50786fa\PhpParser\Comment\Doc($docContent);
    }
}
