<?php

namespace _PhpScoper006a73f0e455\Bug2648Rule;

/** @var array<string> $foo */
$foo = $_GET['bar'];
if (\count($foo) > 0) {
    foreach ($foo as $key => $value) {
        unset($foo[$key]);
    }
    if (\count($foo) > 0) {
        // $foo is actually empty now
    }
}