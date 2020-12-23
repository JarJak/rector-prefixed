<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Symplify\PhpConfigPrinter\Sorter;

final class YamlArgumentSorter
{
    /**
     * @param array<string, mixed> $inOrderKeys Pass array of keys to sort if exists or an associative array following this logic [$key => $valueIfNotExists]
     *
     * @return mixed[]
     */
    public function sortArgumentsByKeyIfExists(array $arrayToSort, array $inOrderKeys) : array
    {
        $argumentsInOrder = [];
        if ($this->isAssociativeArray($inOrderKeys)) {
            foreach ($inOrderKeys as $key => $valueIfNotExists) {
                $argumentsInOrder[$key] = $arrayToSort[$key] ?? $valueIfNotExists;
            }
            return $argumentsInOrder;
        }
        foreach ($inOrderKeys as $key) {
            if (isset($arrayToSort[$key])) {
                $argumentsInOrder[] = $arrayToSort[$key];
            }
        }
        return $argumentsInOrder;
    }
    private function isAssociativeArray(array $array) : bool
    {
        return \array_keys($array) !== \range(0, \count($array) - 1);
    }
}
