<?php

declare (strict_types=1);
namespace Rector\Generic\Rector\FuncCall;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Comments\CommentableNodeResolver;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeRemoval\BreakingRemovalGuard;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @sponsor Thanks https://twitter.com/afilina & Zenika (CAN) for sponsoring this rule - visit them on https://zenika.ca/en/en
 *
 * @see \Rector\Generic\Tests\Rector\FuncCall\RemoveIniGetSetFuncCallRector\RemoveIniGetSetFuncCallRectorTest
 */
final class RemoveIniGetSetFuncCallRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Core\Contract\Rector\ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const KEYS_TO_REMOVE = '$keysToRemove';
    /**
     * @var string[]
     */
    private $keysToRemove = [];
    /**
     * @var BreakingRemovalGuard
     */
    private $breakingRemovalGuard;
    /**
     * @var CommentableNodeResolver
     */
    private $commentableNodeResolver;
    public function __construct(\Rector\NodeRemoval\BreakingRemovalGuard $breakingRemovalGuard, \Rector\Core\Comments\CommentableNodeResolver $commentableNodeResolver)
    {
        $this->breakingRemovalGuard = $breakingRemovalGuard;
        $this->commentableNodeResolver = $commentableNodeResolver;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Remove ini_get by configuration', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample(<<<'CODE_SAMPLE'
ini_get('y2k_compliance');
ini_set('y2k_compliance', 1);
CODE_SAMPLE
, '', [self::KEYS_TO_REMOVE => ['y2k_compliance']])]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param FuncCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isNames($node, ['ini_get', 'ini_set'])) {
            return null;
        }
        if (!isset($node->args[0])) {
            return null;
        }
        $keyValue = $this->getValue($node->args[0]->value);
        if (!\in_array($keyValue, $this->keysToRemove, \true)) {
            return null;
        }
        if ($this->breakingRemovalGuard->isLegalNodeRemoval($node)) {
            $this->removeNode($node);
        } else {
            $commentableNode = $this->commentableNodeResolver->resolve($node);
            $commentableNode->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::COMMENTS, [new \PhpParser\Comment('// @fixme')]);
        }
        return null;
    }
    public function configure(array $configuration) : void
    {
        $this->keysToRemove = $configuration[self::KEYS_TO_REMOVE] ?? [];
    }
}
