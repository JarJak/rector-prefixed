<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Generic\Rector\Class_;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Name;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_;
use _PhpScoper0a2ac50786fa\Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * Covers cases like
 * - https://github.com/FriendsOfPHP/PHP-CS-Fixer/commit/a1cdb4d2dd8f45d731244eed406e1d537218cc66
 * - https://github.com/FriendsOfPHP/PHP-CS-Fixer/commit/614d2e6f7af5a5b0be5363ff536aed2b7ee5a31d
 *
 * @see \Rector\Generic\Tests\Rector\Class_\MergeInterfacesRector\MergeInterfacesRectorTest
 */
final class MergeInterfacesRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector implements \_PhpScoper0a2ac50786fa\Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const OLD_TO_NEW_INTERFACES = '$oldToNewInterfaces';
    /**
     * @var string[]
     */
    private $oldToNewInterfaces = [];
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Merges old interface to a new one, that already has its methods', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
class SomeClass implements SomeInterface, SomeOldInterface
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass implements SomeInterface
{
}
CODE_SAMPLE
, [self::OLD_TO_NEW_INTERFACES => ['SomeOldInterface' => 'SomeInterface']])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        if ($node->implements === []) {
            return null;
        }
        foreach ($node->implements as $key => $implement) {
            $oldInterfaces = \array_keys($this->oldToNewInterfaces);
            if (!$this->isNames($implement, $oldInterfaces)) {
                continue;
            }
            $interface = $this->getName($implement);
            $node->implements[$key] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Name($this->oldToNewInterfaces[$interface]);
        }
        $this->makeImplementsUnique($node);
        return $node;
    }
    public function configure(array $configuration) : void
    {
        $this->oldToNewInterfaces = $configuration[self::OLD_TO_NEW_INTERFACES] ?? [];
    }
    private function makeImplementsUnique(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class) : void
    {
        $alreadyAddedNames = [];
        foreach ($class->implements as $key => $name) {
            $fqnName = $this->getName($name);
            if (\in_array($fqnName, $alreadyAddedNames, \true)) {
                $this->removeImplements($class, $key);
                continue;
            }
            $alreadyAddedNames[] = $fqnName;
        }
    }
}
