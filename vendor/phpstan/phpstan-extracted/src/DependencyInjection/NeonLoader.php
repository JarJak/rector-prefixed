<?php

declare (strict_types=1);
namespace _PhpScoper2a4e7ab1ecbc\PHPStan\DependencyInjection;

use _PhpScoper2a4e7ab1ecbc\PHPStan\File\FileHelper;
class NeonLoader extends \_PhpScoper2a4e7ab1ecbc\_HumbugBox221ad6f1b81f\Nette\DI\Config\Loader
{
    /** @var FileHelper */
    private $fileHelper;
    /** @var string|null */
    private $generateBaselineFile;
    public function __construct(\_PhpScoper2a4e7ab1ecbc\PHPStan\File\FileHelper $fileHelper, ?string $generateBaselineFile)
    {
        $this->fileHelper = $fileHelper;
        $this->generateBaselineFile = $generateBaselineFile;
    }
    /**
     * @param string $file
     * @param bool|null $merge
     * @return mixed[]
     */
    public function load(string $file, ?bool $merge = \true) : array
    {
        if ($this->generateBaselineFile === null) {
            return parent::load($file, $merge);
        }
        $normalizedFile = $this->fileHelper->normalizePath($file);
        if ($this->generateBaselineFile === $normalizedFile) {
            return [];
        }
        return parent::load($file, $merge);
    }
}
