<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\Signature;

interface SignerInterface
{
    public function sign(string $phpCode) : string;
}
