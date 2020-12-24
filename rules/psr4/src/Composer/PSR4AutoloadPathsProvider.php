<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\PSR4\Composer;

use _PhpScopere8e811afab72\Nette\Utils\Json;
use _PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileSystem;
final class PSR4AutoloadPathsProvider
{
    /**
     * @var array<string, string[]>
     */
    private $cachedComposerJsonPSR4AutoloadPaths = [];
    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;
    public function __construct(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileSystem $smartFileSystem)
    {
        $this->smartFileSystem = $smartFileSystem;
    }
    /**
     * @return array<string, string[]>
     */
    public function provide() : array
    {
        if ($this->cachedComposerJsonPSR4AutoloadPaths !== []) {
            return $this->cachedComposerJsonPSR4AutoloadPaths;
        }
        $composerJson = $this->readFileToJsonArray($this->getComposerJsonPath());
        $psr4Autoloads = \array_merge($composerJson['autoload']['psr-4'] ?? [], $composerJson['autoload-dev']['psr-4'] ?? []);
        $this->cachedComposerJsonPSR4AutoloadPaths = $this->removeEmptyNamespaces($psr4Autoloads);
        return $this->cachedComposerJsonPSR4AutoloadPaths;
    }
    /**
     * @return mixed[]
     */
    private function readFileToJsonArray(string $composerJson) : array
    {
        $composerJsonContent = $this->smartFileSystem->readFile($composerJson);
        return \_PhpScopere8e811afab72\Nette\Utils\Json::decode($composerJsonContent, \_PhpScopere8e811afab72\Nette\Utils\Json::FORCE_ARRAY);
    }
    private function getComposerJsonPath() : string
    {
        // assume the project has "composer.json" in root directory
        return \getcwd() . '/composer.json';
    }
    /**
     * @param array<string, string[]> $psr4Autoloads
     * @return array<string, string[]>
     */
    private function removeEmptyNamespaces(array $psr4Autoloads) : array
    {
        return \array_filter($psr4Autoloads, function (string $psr4Autoload) : bool {
            return $psr4Autoload !== '';
        }, \ARRAY_FILTER_USE_KEY);
    }
}
