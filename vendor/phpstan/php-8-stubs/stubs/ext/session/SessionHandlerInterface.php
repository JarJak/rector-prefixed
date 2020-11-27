<?php

namespace _PhpScoper006a73f0e455;

interface SessionHandlerInterface
{
    /** @return bool */
    public function open(string $path, string $name);
    /** @return bool */
    public function close();
    /** @return string */
    public function read(string $id);
    /** @return bool */
    public function write(string $id, string $data);
    /** @return bool */
    public function destroy(string $id);
    /** @return int|bool */
    public function gc(int $max_lifetime);
}
\class_alias('_PhpScoper006a73f0e455\\SessionHandlerInterface', 'SessionHandlerInterface', \false);