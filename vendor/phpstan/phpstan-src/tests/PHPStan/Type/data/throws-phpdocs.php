<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\ThrowsPhpDocs;

use LogicException;
use RuntimeException;
interface Foo
{
    /**
     * @throws RuntimeException
     */
    public function throwRuntimeException();
    /**
     * @throws RuntimeException|LogicException
     */
    public function throwRuntimeAndLogicException();
    /**
     * @throws RuntimeException
     * @throws LogicException
     */
    public function throwRuntimeAndLogicException2();
}
