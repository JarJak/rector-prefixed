<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf\Doctrine\Inflector;

class CachedWordInflector implements \_PhpScoper26e51eeacccf\Doctrine\Inflector\WordInflector
{
    /** @var WordInflector */
    private $wordInflector;
    /** @var string[] */
    private $cache = [];
    public function __construct(\_PhpScoper26e51eeacccf\Doctrine\Inflector\WordInflector $wordInflector)
    {
        $this->wordInflector = $wordInflector;
    }
    public function inflect(string $word) : string
    {
        return $this->cache[$word] ?? ($this->cache[$word] = $this->wordInflector->inflect($word));
    }
}
