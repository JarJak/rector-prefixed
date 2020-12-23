<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\Property_;

use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject\AroundSpaces;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\AbstractDoctrineTagValueNode;
use _PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScoper0a2ac50786fa\Rector\PhpAttribute\Contract\ManyPhpAttributableTagNodeInterface;
use _PhpScoper0a2ac50786fa\Rector\PhpAttribute\Contract\PhpAttributableTagNodeInterface;
final class JoinTableTagValueNode extends \_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\AbstractDoctrineTagValueNode implements \_PhpScoper0a2ac50786fa\Rector\PhpAttribute\Contract\PhpAttributableTagNodeInterface, \_PhpScoper0a2ac50786fa\Rector\PhpAttribute\Contract\ManyPhpAttributableTagNodeInterface
{
    /**
     * @var string
     */
    private const JOIN_COLUMNS = 'joinColumns';
    /**
     * @var string
     */
    private const INVERSE_JOIN_COLUMNS = 'inverseJoinColumns';
    /**
     * @var string
     */
    private $name;
    /**
     * @var JoinColumnTagValueNode[]
     */
    private $joinColumns = [];
    /**
     * @var JoinColumnTagValueNode[]
     */
    private $inverseJoinColumns = [];
    /**
     * @var AroundSpaces|null
     */
    private $inverseJoinColumnsAroundSpaces;
    /**
     * @var AroundSpaces|null
     */
    private $joinColumnsAroundSpaces;
    /**
     * @var string|null
     */
    private $schema;
    /**
     * @param JoinColumnTagValueNode[] $joinColumns
     * @param JoinColumnTagValueNode[] $inverseJoinColumns
     */
    public function __construct(string $name, ?string $schema = null, array $joinColumns = [], array $inverseJoinColumns = [], ?string $originalContent = null, ?\_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject\AroundSpaces $joinColumnsAroundSpaces = null, ?\_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject\AroundSpaces $inverseJoinColumnsAroundSpaces = null)
    {
        $this->name = $name;
        $this->schema = $schema;
        $this->joinColumns = $joinColumns;
        $this->inverseJoinColumns = $inverseJoinColumns;
        $this->resolveOriginalContentSpacingAndOrder($originalContent);
        $this->inverseJoinColumnsAroundSpaces = $inverseJoinColumnsAroundSpaces;
        $this->joinColumnsAroundSpaces = $joinColumnsAroundSpaces;
    }
    public function __toString() : string
    {
        $items = $this->createItems();
        $items = $this->makeKeysExplicit($items);
        return $this->printContentItems($items);
    }
    public function getShortName() : string
    {
        return '_PhpScoper0a2ac50786fa\\@ORM\\JoinTable';
    }
    /**
     * @return mixed[]
     */
    public function getAttributableItems() : array
    {
        $items = [];
        $items['name'] = $this->name;
        if ($this->schema !== null) {
            $items['schema'] = $this->schema;
        }
        return $items;
    }
    /**
     * @return array<string, mixed[]>
     */
    public function provide() : array
    {
        $items = [];
        foreach ($this->joinColumns as $joinColumn) {
            $items[$joinColumn->getShortName()] = $joinColumn->getAttributableItems();
        }
        foreach ($this->inverseJoinColumns as $inverseJoinColumn) {
            $items['@ORM\\InverseJoinColumn'] = $inverseJoinColumn->getAttributableItems();
        }
        return $items;
    }
    public function getAttributeClassName() : string
    {
        return 'TBA';
    }
    /**
     * @return string[]
     */
    private function createItems() : array
    {
        $items = [];
        $items['name'] = \sprintf('"%s"', $this->name);
        if ($this->schema !== null) {
            $items['schema'] = \sprintf('"%s"', $this->schema);
        }
        $joinColumnItems = $this->createJoinColumnItems(self::JOIN_COLUMNS, self::INVERSE_JOIN_COLUMNS);
        return \array_merge($items, $joinColumnItems);
    }
    /**
     * @return array<string, mixed>
     */
    private function createJoinColumnItems(string $joinColumnsKey, string $inverseJoinColumnsKey) : array
    {
        $items = [];
        if ($this->joinColumns !== []) {
            if ($this->joinColumnsAroundSpaces === null) {
                throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
            }
            $items[$joinColumnsKey] = $this->printNestedTag($this->joinColumns, \false, $this->joinColumnsAroundSpaces->getOpeningSpace(), $this->joinColumnsAroundSpaces->getClosingSpace());
        }
        if ($this->inverseJoinColumns !== []) {
            if ($this->inverseJoinColumnsAroundSpaces === null) {
                throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
            }
            $items[$inverseJoinColumnsKey] = $this->printNestedTag($this->inverseJoinColumns, \false, $this->inverseJoinColumnsAroundSpaces->getOpeningSpace(), $this->inverseJoinColumnsAroundSpaces->getClosingSpace());
        }
        return $items;
    }
}
