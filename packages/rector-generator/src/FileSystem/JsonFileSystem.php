<?php

declare (strict_types=1);
namespace Rector\RectorGenerator\FileSystem;

use _PhpScoper26e51eeacccf\Nette\Utils\Json;
use Symplify\SmartFileSystem\SmartFileSystem;
final class JsonFileSystem
{
    /**
     * @var JsonStringFormatter
     */
    private $jsonStringFormatter;
    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;
    public function __construct(\Rector\RectorGenerator\FileSystem\JsonStringFormatter $jsonStringFormatter, \Symplify\SmartFileSystem\SmartFileSystem $smartFileSystem)
    {
        $this->jsonStringFormatter = $jsonStringFormatter;
        $this->smartFileSystem = $smartFileSystem;
    }
    /**
     * @return mixed[]
     */
    public function loadFileToJson(string $filePath) : array
    {
        $fileContent = $this->smartFileSystem->readFile($filePath);
        return \_PhpScoper26e51eeacccf\Nette\Utils\Json::decode($fileContent, \_PhpScoper26e51eeacccf\Nette\Utils\Json::FORCE_ARRAY);
    }
    /**
     * @param mixed[] $json
     */
    public function saveJsonToFile(string $filePath, array $json) : void
    {
        $content = \_PhpScoper26e51eeacccf\Nette\Utils\Json::encode($json, \_PhpScoper26e51eeacccf\Nette\Utils\Json::PRETTY);
        $content = $this->jsonStringFormatter->inlineSections($content, ['keywords', 'bin']);
        $content = $this->jsonStringFormatter->inlineAuthors($content);
        // make sure there is newline in the end
        $content = \trim($content) . \PHP_EOL;
        $this->smartFileSystem->dumpFile($filePath, $content);
    }
}
