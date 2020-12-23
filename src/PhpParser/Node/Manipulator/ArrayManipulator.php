<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\Manipulator;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayItem;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Scalar;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\String_;
use _PhpScoper0a2ac50786fa\Rector\ChangesReporting\Collector\RectorChangeCollector;
final class ArrayManipulator
{
    /**
     * @var RectorChangeCollector
     */
    private $rectorChangeCollector;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\ChangesReporting\Collector\RectorChangeCollector $rectorChangeCollector)
    {
        $this->rectorChangeCollector = $rectorChangeCollector;
    }
    public function isArrayOnlyScalarValues(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_ $array) : bool
    {
        foreach ($array->items as $arrayItem) {
            if (!$arrayItem instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            if ($arrayItem->value instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_) {
                if (!$this->isArrayOnlyScalarValues($arrayItem->value)) {
                    return \false;
                }
                continue;
            }
            if ($arrayItem->value instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar) {
                continue;
            }
            return \false;
        }
        return \true;
    }
    public function addItemToArrayUnderKey(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_ $array, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayItem $newArrayItem, string $key) : void
    {
        foreach ($array->items as $item) {
            if ($item === null) {
                continue;
            }
            if ($this->hasKeyName($item, $key)) {
                if (!$item->value instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_) {
                    continue;
                }
                $item->value->items[] = $newArrayItem;
                return;
            }
        }
        $array->items[] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayItem(new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_([$newArrayItem]), new \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\String_($key));
    }
    public function findItemInInArrayByKeyAndUnset(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Array_ $array, string $keyName) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayItem
    {
        foreach ($array->items as $i => $item) {
            if ($item === null) {
                continue;
            }
            if (!$this->hasKeyName($item, $keyName)) {
                continue;
            }
            $removedArrayItem = $array->items[$i];
            if ($removedArrayItem === null) {
                continue;
            }
            // remove + recount for the printer
            unset($array->items[$i]);
            $this->rectorChangeCollector->notifyNodeFileInfo($removedArrayItem);
            return $item;
        }
        return null;
    }
    public function hasKeyName(\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\ArrayItem $arrayItem, string $name) : bool
    {
        if (!$arrayItem->key instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\String_) {
            return \false;
        }
        return $arrayItem->key->value === $name;
    }
}
