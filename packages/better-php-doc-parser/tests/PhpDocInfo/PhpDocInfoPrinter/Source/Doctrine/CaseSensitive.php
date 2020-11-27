<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocInfo\PhpDocInfoPrinter\Source\Doctrine;

use _PhpScoper006a73f0e455\Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(
 *   name="Content_Status", indexes={
 *     @Orm\Index(name="value_idx", columns={"value"}),
 *     @Orm\Index(name="dateFrom_idx", columns={"dateFrom"}),
 *     @Orm\Index(name="dateTo_idx", columns={"dateTo"}),
 *   }
 * )
 */
final class CaseSensitive
{
}