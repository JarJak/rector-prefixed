<?php

declare (strict_types=1);
namespace Rector\Core\Rector\AbstractRector;

use PhpParser\Node;
use Rector\Core\PhpParser\Node\Manipulator\ConstFetchManipulator;
/**
 * This could be part of @see AbstractRector, but decopuling to trait
 * makes clear what code has 1 purpose.
 */
trait ConstFetchAnalyzerTrait
{
    /**
     * @var ConstFetchManipulator
     */
    private $constFetchManipulator;
    /**
     * @required
     */
    public function autowireConstFetchAnalyzerTrait(\Rector\Core\PhpParser\Node\Manipulator\ConstFetchManipulator $constFetchManipulator) : void
    {
        $this->constFetchManipulator = $constFetchManipulator;
    }
    public function isFalse(\PhpParser\Node $node) : bool
    {
        return $this->constFetchManipulator->isFalse($node);
    }
    public function isTrue(\PhpParser\Node $node) : bool
    {
        return $this->constFetchManipulator->isTrue($node);
    }
    public function isBool(\PhpParser\Node $node) : bool
    {
        return $this->constFetchManipulator->isBool($node);
    }
    public function isNull(\PhpParser\Node $node) : bool
    {
        return $this->constFetchManipulator->isNull($node);
    }
}
