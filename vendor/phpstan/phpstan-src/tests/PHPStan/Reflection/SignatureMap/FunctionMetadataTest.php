<?php

declare (strict_types=1);
namespace PHPStan\Reflection\SignatureMap;

use _PhpScoper006a73f0e455\Nette\Schema\Expect;
use _PhpScoper006a73f0e455\Nette\Schema\Processor;
use PHPStan\Testing\TestCase;
class FunctionMetadataTest extends \PHPStan\Testing\TestCase
{
    public function testSchema() : void
    {
        $data = (require __DIR__ . '/../../../../resources/functionMetadata.php');
        $this->assertIsArray($data);
        $processor = new \_PhpScoper006a73f0e455\Nette\Schema\Processor();
        $processor->process(\_PhpScoper006a73f0e455\Nette\Schema\Expect::arrayOf(\_PhpScoper006a73f0e455\Nette\Schema\Expect::structure(['hasSideEffects' => \_PhpScoper006a73f0e455\Nette\Schema\Expect::bool()->required()])->required())->required(), $data);
    }
}