<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\PhpDocInfo;

use PhpParser\Node;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode;
use Rector\BetterPhpDocParser\Attributes\Ast\AttributeAwareNodeFactory;
use Rector\BetterPhpDocParser\Attributes\Attribute\Attribute;
use Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface;
use Rector\BetterPhpDocParser\Contract\PhpDocNodeFactoryInterface;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocRemover;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\BetterPhpDocParser\PhpDocParser\BetterPhpDocParser;
use Rector\BetterPhpDocParser\ValueObject\StartAndEnd;
use Rector\Core\Configuration\CurrentNodeProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\StaticTypeMapper;
final class PhpDocInfoFactory
{
    /**
     * @var PhpDocParser
     */
    private $betterPhpDocParser;
    /**
     * @var Lexer
     */
    private $lexer;
    /**
     * @var CurrentNodeProvider
     */
    private $currentNodeProvider;
    /**
     * @var StaticTypeMapper
     */
    private $staticTypeMapper;
    /**
     * @var AttributeAwareNodeFactory
     */
    private $attributeAwareNodeFactory;
    /**
     * @var PhpDocTypeChanger
     */
    private $phpDocTypeChanger;
    /**
     * @var PhpDocRemover
     */
    private $phpDocRemover;
    public function __construct(\Rector\BetterPhpDocParser\Attributes\Ast\AttributeAwareNodeFactory $attributeAwareNodeFactory, \Rector\Core\Configuration\CurrentNodeProvider $currentNodeProvider, \PHPStan\PhpDocParser\Lexer\Lexer $lexer, \Rector\BetterPhpDocParser\PhpDocParser\BetterPhpDocParser $betterPhpDocParser, \Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocRemover $phpDocRemover, \Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger $phpDocTypeChanger, \Rector\StaticTypeMapper\StaticTypeMapper $staticTypeMapper)
    {
        $this->betterPhpDocParser = $betterPhpDocParser;
        $this->lexer = $lexer;
        $this->currentNodeProvider = $currentNodeProvider;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->attributeAwareNodeFactory = $attributeAwareNodeFactory;
        $this->phpDocTypeChanger = $phpDocTypeChanger;
        $this->phpDocRemover = $phpDocRemover;
    }
    public function createFromNodeOrEmpty(\PhpParser\Node $node) : \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo
    {
        $phpDocInfo = $this->createFromNode($node);
        if ($phpDocInfo !== null) {
            return $phpDocInfo;
        }
        return $this->createEmpty($node);
    }
    public function createFromNode(\PhpParser\Node $node) : ?\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo
    {
        /** needed for @see PhpDocNodeFactoryInterface */
        $this->currentNodeProvider->setNode($node);
        $docComment = $node->getDocComment();
        if ($docComment === null) {
            if ($node->getComments() !== []) {
                return null;
            }
            // create empty node
            $content = '';
            $tokens = [];
            $phpDocNode = new \Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode([]);
        } else {
            $content = $docComment->getText();
            $tokens = $this->lexer->tokenize($content);
            $phpDocNode = $this->parseTokensToPhpDocNode($tokens);
            $this->setPositionOfLastToken($phpDocNode);
        }
        return $this->createFromPhpDocNode($phpDocNode, $content, $tokens, $node);
    }
    public function createEmpty(\PhpParser\Node $node) : \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo
    {
        /** needed for @see PhpDocNodeFactoryInterface */
        $this->currentNodeProvider->setNode($node);
        $attributeAwarePhpDocNode = new \Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode([]);
        return $this->createFromPhpDocNode($attributeAwarePhpDocNode, '', [], $node);
    }
    /**
     * @param mixed[][] $tokens
     */
    private function parseTokensToPhpDocNode(array $tokens) : \Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode
    {
        $tokenIterator = new \PHPStan\PhpDocParser\Parser\TokenIterator($tokens);
        return $this->betterPhpDocParser->parse($tokenIterator);
    }
    /**
     * Needed for printing
     */
    private function setPositionOfLastToken(\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode $attributeAwarePhpDocNode) : void
    {
        if ($attributeAwarePhpDocNode->children === []) {
            return;
        }
        $phpDocChildNodes = $attributeAwarePhpDocNode->children;
        /** @var AttributeAwareNodeInterface $lastChildNode */
        $lastChildNode = \array_pop($phpDocChildNodes);
        /** @var StartAndEnd $startAndEnd */
        $startAndEnd = $lastChildNode->getAttribute(\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute::START_END);
        if ($startAndEnd !== null) {
            $attributeAwarePhpDocNode->setAttribute(\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute::LAST_TOKEN_POSITION, $startAndEnd->getEnd());
        }
    }
    /**
     * @param mixed[] $tokens
     */
    private function createFromPhpDocNode(\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode $attributeAwarePhpDocNode, string $content, array $tokens, \PhpParser\Node $node) : \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo
    {
        /** @var AttributeAwarePhpDocNode $attributeAwarePhpDocNode */
        $attributeAwarePhpDocNode = $this->attributeAwareNodeFactory->createFromNode($attributeAwarePhpDocNode, $content);
        $phpDocInfo = new \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo($attributeAwarePhpDocNode, $tokens, $content, $this->staticTypeMapper, $node, $this->phpDocTypeChanger, $this->phpDocRemover, $this->attributeAwareNodeFactory);
        $node->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO, $phpDocInfo);
        return $phpDocInfo;
    }
}
