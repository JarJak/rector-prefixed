<?php

namespace _PhpScopera143bcca66cb\Bug4070;

\array_shift($argv);
while ($argv) {
    $arg = \array_shift($argv);
    if ($arg === 'foo') {
        continue;
    }
    die;
}
echo "finished\n";
