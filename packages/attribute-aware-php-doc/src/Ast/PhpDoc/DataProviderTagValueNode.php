<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\AttributeAwarePhpDoc\Ast\PhpDoc;

use _PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Attributes\Attribute\AttributeTrait;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface;
final class DataProviderTagValueNode implements \_PhpScoper0a2ac50786fa\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode, \_PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface
{
    use AttributeTrait;
    /**
     * @var string
     */
    private $method;
    public function __construct(string $method)
    {
        $this->method = $method;
    }
    public function __toString() : string
    {
        return $this->method;
    }
    public function getMethod() : string
    {
        return $this->method;
    }
    public function getMethodName() : string
    {
        return \trim($this->method, '()');
    }
    public function changeMethod(string $method) : void
    {
        $this->method = $method;
    }
}
