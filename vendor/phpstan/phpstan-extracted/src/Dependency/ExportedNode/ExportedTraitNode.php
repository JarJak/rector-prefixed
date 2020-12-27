<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Dependency\ExportedNode;

use JsonSerializable;
use RectorPrefix20201227\PHPStan\Dependency\ExportedNode;
class ExportedTraitNode implements \RectorPrefix20201227\PHPStan\Dependency\ExportedNode, \JsonSerializable
{
    /** @var string */
    private $traitName;
    public function __construct(string $traitName)
    {
        $this->traitName = $traitName;
    }
    public function equals(\RectorPrefix20201227\PHPStan\Dependency\ExportedNode $node) : bool
    {
        return \false;
    }
    /**
     * @param mixed[] $properties
     * @return self
     */
    public static function __set_state(array $properties) : \RectorPrefix20201227\PHPStan\Dependency\ExportedNode
    {
        return new self($properties['traitName']);
    }
    /**
     * @param mixed[] $data
     * @return self
     */
    public static function decode(array $data) : \RectorPrefix20201227\PHPStan\Dependency\ExportedNode
    {
        return new self($data['traitName']);
    }
    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return ['type' => self::class, 'data' => ['traitName' => $this->traitName]];
    }
}
