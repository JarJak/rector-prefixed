<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\DoctrineGeneratedValue;

use _PhpScoper006a73f0e455\Doctrine\ORM\Mapping as ORM;
class GeneratedValueWithStrategy
{
    /**
     * @ORM\GeneratedValue("AUTO")
     */
    private $implicit;
    /**
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $explicitWithoutQuotes;
}