<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject;

use _PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException;
final class StartAndEnd
{
    /**
     * @var int
     */
    private $start;
    /**
     * @var int
     */
    private $end;
    public function __construct(int $start, int $end)
    {
        if ($end < $start) {
            throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
        }
        $this->start = $start;
        $this->end = $end;
    }
    public function getStart() : int
    {
        return $this->start;
    }
    public function getEnd() : int
    {
        return $this->end;
    }
}
