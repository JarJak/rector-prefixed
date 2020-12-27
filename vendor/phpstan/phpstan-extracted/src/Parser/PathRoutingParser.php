<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Parser;

use RectorPrefix20201227\PHPStan\File\FileHelper;
class PathRoutingParser implements \RectorPrefix20201227\PHPStan\Parser\Parser
{
    /** @var FileHelper */
    private $fileHelper;
    /** @var Parser */
    private $currentPhpVersionRichParser;
    /** @var Parser */
    private $currentPhpVersionSimpleParser;
    /** @var Parser */
    private $php8Parser;
    public function __construct(\RectorPrefix20201227\PHPStan\File\FileHelper $fileHelper, \RectorPrefix20201227\PHPStan\Parser\Parser $currentPhpVersionRichParser, \RectorPrefix20201227\PHPStan\Parser\Parser $currentPhpVersionSimpleParser, \RectorPrefix20201227\PHPStan\Parser\Parser $php8Parser)
    {
        $this->fileHelper = $fileHelper;
        $this->currentPhpVersionRichParser = $currentPhpVersionRichParser;
        $this->currentPhpVersionSimpleParser = $currentPhpVersionSimpleParser;
        $this->php8Parser = $php8Parser;
    }
    public function parseFile(string $file) : array
    {
        $file = $this->fileHelper->normalizePath($file, '/');
        if (\strpos($file, 'vendor/jetbrains/phpstorm-stubs') !== \false) {
            return $this->php8Parser->parseFile($file);
        }
        return $this->currentPhpVersionRichParser->parseFile($file);
    }
    public function parseString(string $sourceCode) : array
    {
        return $this->currentPhpVersionSimpleParser->parseString($sourceCode);
    }
}
