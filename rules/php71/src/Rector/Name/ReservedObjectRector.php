<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Php71\Rector\Name;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Identifier;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Name;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Namespace_;
use _PhpScoper0a2ac50786fa\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://wiki.php.net/rfc/object-typehint
 * @see https://github.com/cebe/yii2/commit/9548a212ecf6e50fcdb0e5ba6daad88019cfc544
 * @see \Rector\Php71\Tests\Rector\Name\ReservedObjectRector\ReservedObjectRectorTest
 */
final class ReservedObjectRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector implements \_PhpScoper0a2ac50786fa\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const RESERVED_KEYWORDS_TO_REPLACEMENTS = '$reservedKeywordsToReplacements';
    /**
     * @var string[]
     */
    private $reservedKeywordsToReplacements = [];
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes reserved "Object" name to "<Smart>Object" where <Smart> can be configured', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class Object
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SmartObject
{
}
CODE_SAMPLE
, [self::RESERVED_KEYWORDS_TO_REPLACEMENTS => ['ReservedObject' => 'SmartObject', 'Object' => 'AnotherSmartObject']])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Identifier::class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Name::class];
    }
    /**
     * @param Identifier|Name $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        if ($node instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Identifier) {
            return $this->processIdentifier($node);
        }
        return $this->processName($node);
    }
    public function configure(array $configuration) : void
    {
        $this->reservedKeywordsToReplacements = $configuration[self::RESERVED_KEYWORDS_TO_REPLACEMENTS] ?? [];
    }
    private function processIdentifier(\_PhpScoper0a2ac50786fa\PhpParser\Node\Identifier $identifier) : \_PhpScoper0a2ac50786fa\PhpParser\Node\Identifier
    {
        foreach ($this->reservedKeywordsToReplacements as $reservedKeyword => $replacement) {
            if (!$this->isName($identifier, $reservedKeyword)) {
                continue;
            }
            $identifier->name = $replacement;
            return $identifier;
        }
        return $identifier;
    }
    private function processName(\_PhpScoper0a2ac50786fa\PhpParser\Node\Name $name) : \_PhpScoper0a2ac50786fa\PhpParser\Node\Name
    {
        // we look for "extends <Name>"
        $parentNode = $name->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        // "Object" can part of namespace name
        if ($parentNode instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Namespace_) {
            return $name;
        }
        // process lass part
        foreach ($this->reservedKeywordsToReplacements as $reservedKeyword => $replacement) {
            if (\strtolower($name->getLast()) === \strtolower($reservedKeyword)) {
                $name->parts[\count((array) $name->parts) - 1] = $replacement;
                // invoke override
                $name->setAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE, null);
            }
        }
        return $name;
    }
}
