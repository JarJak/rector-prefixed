<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\BetterNodeFinder;
use _PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileSystem;
/**
 * This could be part of @see AbstractRector, but decopuling to trait
 * makes clear what code has 1 purpose.
 */
trait BetterStandardPrinterTrait
{
    /**
     * @var BetterNodeFinder
     */
    protected $betterNodeFinder;
    /**
     * @var BetterStandardPrinter
     */
    protected $betterStandardPrinter;
    /**
     * @var SmartFileSystem
     */
    protected $smartFileSystem;
    /**
     * @required
     */
    public function autowireBetterStandardPrinterTrait(\_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Printer\BetterStandardPrinter $betterStandardPrinter, \_PhpScoper0a2ac50786fa\Rector\Core\PhpParser\Node\BetterNodeFinder $betterNodeFinder, \_PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileSystem $smartFileSystem) : void
    {
        $this->betterStandardPrinter = $betterStandardPrinter;
        $this->betterNodeFinder = $betterNodeFinder;
        $this->smartFileSystem = $smartFileSystem;
    }
    /**
     * @param Node|Node[]|null $node
     */
    public function print($node) : string
    {
        return $this->betterStandardPrinter->print($node);
    }
    /**
     * @param Node|Node[]|null $node
     */
    public function printWithoutComments($node) : string
    {
        return $this->betterStandardPrinter->printWithoutComments($node);
    }
    /**
     * @param Node[] $nodes
     */
    public function printToFile(array $nodes, string $filePath) : void
    {
        $content = $this->betterStandardPrinter->prettyPrintFile($nodes);
        $this->smartFileSystem->dumpFile($filePath, $content);
    }
    /**
     * Removes all comments from both nodes
     *
     * @param Node|Node[]|null $firstNode
     * @param Node|Node[]|null $secondNode
     */
    protected function areNodesEqual($firstNode, $secondNode) : bool
    {
        return $this->betterStandardPrinter->areNodesEqual($firstNode, $secondNode);
    }
}
