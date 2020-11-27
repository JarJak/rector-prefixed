<?php

declare (strict_types=1);
namespace Rector\Doctrine\Tests\Rector\ClassMethod\ChangeReturnTypeOfClassMethodWithGetIdRector\Source;

use _PhpScoper26e51eeacccf\Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 */
class Building
{
    private $id;
    public function getId() : \_PhpScoper26e51eeacccf\Ramsey\Uuid\UuidInterface
    {
        return $this->id;
    }
}
