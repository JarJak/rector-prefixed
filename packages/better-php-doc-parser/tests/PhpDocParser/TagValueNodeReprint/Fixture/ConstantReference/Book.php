<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\ConstantReference;

use Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Source\ApiFilter;
use _PhpScoper006a73f0e455\Doctrine\ORM\Mapping as ORM;
final class Book
{
    /**
     * @ApiFilter(Book::class)
     * @ORM\Column()
     */
    public $filterable;
}