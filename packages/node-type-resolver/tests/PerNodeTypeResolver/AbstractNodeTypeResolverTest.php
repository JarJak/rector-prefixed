<?php

declare (strict_types=1);
namespace Rector\NodeTypeResolver\Tests\PerNodeTypeResolver;

use PhpParser\Node;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\Testing\TestingParser\TestingParser;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
abstract class AbstractNodeTypeResolverTest extends \Symplify\PackageBuilder\Testing\AbstractKernelTestCase
{
    /**
     * @var NodeTypeResolver
     */
    protected $nodeTypeResolver;
    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;
    /**
     * @var TestingParser
     */
    private $testingParser;
    protected function setUp() : void
    {
        $this->bootKernel(\Rector\Core\HttpKernel\RectorKernel::class);
        $this->betterNodeFinder = self::$container->get(\Rector\Core\PhpParser\Node\BetterNodeFinder::class);
        $this->testingParser = self::$container->get(\Rector\Testing\TestingParser\TestingParser::class);
        $this->nodeTypeResolver = self::$container->get(\Rector\NodeTypeResolver\NodeTypeResolver::class);
    }
    /**
     * @return Node[]
     */
    protected function getNodesForFileOfType(string $file, string $type) : array
    {
        $nodes = $this->testingParser->parseFileToDecoratedNodes($file);
        return $this->betterNodeFinder->findInstanceOf($nodes, $type);
    }
}