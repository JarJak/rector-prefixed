<?php

declare (strict_types=1);
namespace Rector\Doctrine\Tests\Rector\MethodCall\ChangeGetUuidMethodCallToGetIdRector\Source;

use RectorPrefix20210118\Doctrine\ORM\Mapping as ORM;
use RectorPrefix20210118\Ramsey\Uuid\UuidInterface;
/**
 * @ORM\Entity
 */
class Car
{
    private $uuid;
    public function getUuid() : \RectorPrefix20210118\Ramsey\Uuid\UuidInterface
    {
        return $this->uuid;
    }
}
