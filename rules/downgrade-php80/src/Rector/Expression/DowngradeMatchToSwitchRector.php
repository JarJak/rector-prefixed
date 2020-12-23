<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\DowngradePhp80\Rector\Expression;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Match_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\MatchArm;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Break_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Case_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Expression;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Switch_;
use _PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://wiki.php.net/rfc/match_expression_v2
 *
 * @see \Rector\DowngradePhp80\Tests\Rector\Expression\DowngradeMatchToSwitchRector\DowngradeMatchToSwitchRectorTest
 */
final class DowngradeMatchToSwitchRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Downgrade match() to switch()', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $message = match ($statusCode) {
            200, 300 => null,
            400 => 'not found',
            default => 'unknown status code',
        };
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        switch ($statusCode) {
            case 200:
            case 300:
                $message = null;
                break;
            case 400:
                $message = 'not found';
                break;
            default:
                $message = 'unknown status code';
                break;
        }
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Expression::class];
    }
    /**
     * @param Expression $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        if (!$node->expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign) {
            return null;
        }
        $assign = $node->expr;
        if (!$assign->expr instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Match_) {
            return null;
        }
        /** @var Match_ $match */
        $match = $assign->expr;
        $switchCases = $this->createSwitchCasesFromMatchArms((array) $match->arms, $assign->var);
        return new \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Switch_($match->cond, $switchCases);
    }
    /**
     * @param MatchArm[] $matchArms
     * @return Case_[]
     */
    private function createSwitchCasesFromMatchArms(array $matchArms, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr $assignVarExpr) : array
    {
        $switchCases = [];
        foreach ($matchArms as $matchArm) {
            if (\count((array) $matchArm->conds) > 1) {
                $lastCase = null;
                foreach ((array) $matchArm->conds as $matchArmCond) {
                    $lastCase = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Case_($matchArmCond);
                    $switchCases[] = $lastCase;
                }
                if (!$lastCase instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Case_) {
                    throw new \_PhpScoper0a2ac50786fa\Rector\Core\Exception\ShouldNotHappenException();
                }
                $lastCase->stmts = $this->createSwitchStmts($matchArm, $assignVarExpr);
            } else {
                $stmts = $this->createSwitchStmts($matchArm, $assignVarExpr);
                $switchCases[] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Case_($matchArm->conds[0] ?? null, $stmts);
            }
        }
        return $switchCases;
    }
    /**
     * @return Stmt[]
     */
    private function createSwitchStmts(\_PhpScoper0a2ac50786fa\PhpParser\Node\MatchArm $matchArm, \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr $assignVarExpr) : array
    {
        $stmts = [];
        $stmts[] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Expression(new \_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\Assign($assignVarExpr, $matchArm->body));
        $stmts[] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Break_();
        return $stmts;
    }
}
