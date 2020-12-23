<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\PhpDocParser;

use _PhpScoper0a2ac50786fa\Nette\Utils\Strings;
use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Use_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\UseUse;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
/**
 * Matches "@ORM\Entity" to FQN names based on use imports in the file
 */
final class ClassAnnotationMatcher
{
    /**
     * @var string[]
     */
    private $fullyQualifiedNameByHash = [];
    public function resolveTagFullyQualifiedName(string $tag, \_PhpScoper0a2ac50786fa\PhpParser\Node $node) : string
    {
        $uniqueHash = $tag . \spl_object_hash($node);
        if (isset($this->fullyQualifiedNameByHash[$uniqueHash])) {
            return $this->fullyQualifiedNameByHash[$uniqueHash];
        }
        $tag = \ltrim($tag, '@');
        /** @var Use_[] $useNodes */
        $useNodes = (array) $node->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::USE_NODES);
        $fullyQualifiedClass = $this->resolveFullyQualifiedClass($useNodes, $node, $tag);
        $this->fullyQualifiedNameByHash[$uniqueHash] = $fullyQualifiedClass;
        return $fullyQualifiedClass;
    }
    /**
     * @param Use_[] $uses
     */
    private function resolveFullyQualifiedClass(array $uses, \_PhpScoper0a2ac50786fa\PhpParser\Node $node, string $tag) : string
    {
        if ($uses === []) {
            /** @var string|null $namespace */
            $namespace = $node->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::NAMESPACE_NAME);
            if ($namespace !== null) {
                $namespacedTag = $namespace . '\\' . $tag;
                if (\class_exists($namespacedTag)) {
                    return $namespacedTag;
                }
            }
            return $tag;
        }
        return $this->matchFullAnnotationClassWithUses($tag, $uses) ?? $tag;
    }
    /**
     * @param Use_[] $uses
     */
    private function matchFullAnnotationClassWithUses(string $tag, array $uses) : ?string
    {
        foreach ($uses as $use) {
            foreach ($use->uses as $useUse) {
                if (!$this->isUseMatchingName($tag, $useUse)) {
                    continue;
                }
                return $this->resolveName($tag, $useUse);
            }
        }
        return null;
    }
    private function isUseMatchingName(string $tag, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\UseUse $useUse) : bool
    {
        $shortName = $useUse->alias !== null ? $useUse->alias->name : $useUse->name->getLast();
        $shortNamePattern = \preg_quote($shortName, '#');
        return (bool) \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::match($tag, '#' . $shortNamePattern . '(\\\\[\\w]+)?#i');
    }
    private function resolveName(string $tag, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\UseUse $useUse) : string
    {
        if ($useUse->alias === null) {
            return $useUse->name->toString();
        }
        $unaliasedShortClass = \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::substring($tag, \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::length($useUse->alias->toString()));
        if (\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::startsWith($unaliasedShortClass, '\\')) {
            return $useUse->name . $unaliasedShortClass;
        }
        return $useUse->name . '\\' . $unaliasedShortClass;
    }
}
