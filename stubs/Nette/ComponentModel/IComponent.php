<?php

declare (strict_types=1);
namespace _PhpScoper006a73f0e455\Nette\ComponentModel;

if (\interface_exists('_PhpScoper006a73f0e455\\Nette\\ComponentModel\\IComponent')) {
    return;
}
interface IComponent
{
    /**
     * Returns component specified by name or path.
     * @throws \Nette\InvalidArgumentException  if component doesn't exist
     */
    function getComponent(string $name) : ?\_PhpScoper006a73f0e455\Nette\ComponentModel\IComponent;
}
