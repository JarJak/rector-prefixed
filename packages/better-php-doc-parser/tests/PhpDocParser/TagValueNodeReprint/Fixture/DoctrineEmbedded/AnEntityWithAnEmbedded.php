<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\DoctrineEmbedded;

use _PhpScoper567b66d83109\Doctrine\ORM\Mapping as ORM;
use Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Source\Embeddable;
final class AnEntityWithAnEmbedded
{
    /**
     * @ORM\Embedded(class="Embeddable")
     */
    private $embedded;
}
