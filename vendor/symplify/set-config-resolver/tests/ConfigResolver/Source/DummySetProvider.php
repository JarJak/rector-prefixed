<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Symplify\SetConfigResolver\Tests\ConfigResolver\Source;

use _PhpScoper0a2ac50786fa\Symplify\SetConfigResolver\Contract\SetProviderInterface;
use _PhpScoper0a2ac50786fa\Symplify\SetConfigResolver\Provider\AbstractSetProvider;
use _PhpScoper0a2ac50786fa\Symplify\SetConfigResolver\ValueObject\Set;
use _PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo;
final class DummySetProvider extends \_PhpScoper0a2ac50786fa\Symplify\SetConfigResolver\Provider\AbstractSetProvider implements \_PhpScoper0a2ac50786fa\Symplify\SetConfigResolver\Contract\SetProviderInterface
{
    /**
     * @var Set[]
     */
    private $sets = [];
    public function __construct()
    {
        $this->sets[] = new \_PhpScoper0a2ac50786fa\Symplify\SetConfigResolver\ValueObject\Set('some_set', new \_PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/../Source/some_set.yaml'));
        $this->sets[] = new \_PhpScoper0a2ac50786fa\Symplify\SetConfigResolver\ValueObject\Set('some_php_set', new \_PhpScoper0a2ac50786fa\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/../Source/some_php_set.php'));
    }
    /**
     * @return Set[]
     */
    public function provide() : array
    {
        return $this->sets;
    }
}
