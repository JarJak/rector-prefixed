<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Symfony\Validator\Constraints;

use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\ShortNameAwareTagInterface;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\SilentKeyNodeInterface;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\AbstractTagValueNode;
/**
 * @see \Rector\BetterPhpDocParser\PhpDocNodeFactory\Symfony\Validator\Constraints\AssertTypePhpDocNodeFactory
 *
 * @see \Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\AssertTypeTagValueNodeTest
 */
final class AssertTypeTagValueNode extends \_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\AbstractTagValueNode implements \_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\ShortNameAwareTagInterface, \_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\SilentKeyNodeInterface
{
    public function getShortName() : string
    {
        return '_PhpScoper0a2ac50786fa\\@Assert\\Type';
    }
    public function getSilentKey() : string
    {
        return 'type';
    }
}
