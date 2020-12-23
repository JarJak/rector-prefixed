<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Php74\Rector\FuncCall;

use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Arg;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Expr\FuncCall;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\LNumber;
use _PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://wiki.php.net/rfc/deprecations_php_7_4 (not confirmed yet)
 * @see https://3v4l.org/kLdtB
 * @see \Rector\Php74\Tests\Rector\FuncCall\MbStrrposEncodingArgumentPositionRector\MbStrrposEncodingArgumentPositionRectorTest
 */
final class MbStrrposEncodingArgumentPositionRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change mb_strrpos() encoding argument position', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('mb_strrpos($text, "abc", "UTF-8");', 'mb_strrpos($text, "abc", 0, "UTF-8");')]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param FuncCall $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
    {
        if (!$this->isName($node, 'mb_strrpos')) {
            return null;
        }
        if (!isset($node->args[2])) {
            return null;
        }
        if (isset($node->args[3])) {
            return null;
        }
        if ($this->getStaticType($node->args[2]->value) instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\IntegerType) {
            return null;
        }
        $node->args[3] = $node->args[2];
        $node->args[2] = new \_PhpScoper0a2ac50786fa\PhpParser\Node\Arg(new \_PhpScoper0a2ac50786fa\PhpParser\Node\Scalar\LNumber(0));
        return $node;
    }
}
