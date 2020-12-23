<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Testing;

use _PhpScoper0a2ac50786fa\PHPStan\Analyser\Error;
use _PhpScoper0a2ac50786fa\PHPStan\Command\AnalysisResult;
use _PhpScoper0a2ac50786fa\PHPStan\Command\ErrorsConsoleStyle;
use _PhpScoper0a2ac50786fa\PHPStan\Command\Output;
use _PhpScoper0a2ac50786fa\PHPStan\Command\Symfony\SymfonyOutput;
use _PhpScoper0a2ac50786fa\PHPStan\Command\Symfony\SymfonyStyle;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\StringInput;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Output\StreamOutput;
abstract class ErrorFormatterTestCase extends \_PhpScoper0a2ac50786fa\PHPStan\Testing\TestCase
{
    protected const DIRECTORY_PATH = '/data/folder/with space/and unicode 😃/project';
    /** @var StreamOutput|null */
    private $outputStream = null;
    /** @var Output|null */
    private $output = null;
    private function getOutputStream() : \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Output\StreamOutput
    {
        if (\PHP_VERSION_ID >= 80000 && \DIRECTORY_SEPARATOR === '\\') {
            $this->markTestSkipped('Skipped because of https://github.com/symfony/symfony/issues/37508');
        }
        if ($this->outputStream === null) {
            $resource = \fopen('php://memory', 'w', \false);
            if ($resource === \false) {
                throw new \_PhpScoper0a2ac50786fa\PHPStan\ShouldNotHappenException();
            }
            $this->outputStream = new \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Output\StreamOutput($resource);
        }
        return $this->outputStream;
    }
    protected function getOutput() : \_PhpScoper0a2ac50786fa\PHPStan\Command\Output
    {
        if ($this->output === null) {
            $errorConsoleStyle = new \_PhpScoper0a2ac50786fa\PHPStan\Command\ErrorsConsoleStyle(new \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Symfony\Component\Console\Input\StringInput(''), $this->getOutputStream());
            $this->output = new \_PhpScoper0a2ac50786fa\PHPStan\Command\Symfony\SymfonyOutput($this->getOutputStream(), new \_PhpScoper0a2ac50786fa\PHPStan\Command\Symfony\SymfonyStyle($errorConsoleStyle));
        }
        return $this->output;
    }
    protected function getOutputContent() : string
    {
        \rewind($this->getOutputStream()->getStream());
        $contents = \stream_get_contents($this->getOutputStream()->getStream());
        if ($contents === \false) {
            throw new \_PhpScoper0a2ac50786fa\PHPStan\ShouldNotHappenException();
        }
        return $this->rtrimMultiline($contents);
    }
    protected function getAnalysisResult(int $numFileErrors, int $numGenericErrors) : \_PhpScoper0a2ac50786fa\PHPStan\Command\AnalysisResult
    {
        if ($numFileErrors > 4 || $numFileErrors < 0 || $numGenericErrors > 2 || $numGenericErrors < 0) {
            throw new \Exception();
        }
        $fileErrors = \array_slice([new \_PhpScoper0a2ac50786fa\PHPStan\Analyser\Error('Foo', self::DIRECTORY_PATH . '/folder with unicode 😃/file name with "spaces" and unicode 😃.php', 4), new \_PhpScoper0a2ac50786fa\PHPStan\Analyser\Error('Foo', self::DIRECTORY_PATH . '/foo.php', 1), new \_PhpScoper0a2ac50786fa\PHPStan\Analyser\Error("Bar\nBar2", self::DIRECTORY_PATH . '/foo.php', 5), new \_PhpScoper0a2ac50786fa\PHPStan\Analyser\Error("Bar\nBar2", self::DIRECTORY_PATH . '/folder with unicode 😃/file name with "spaces" and unicode 😃.php', 2)], 0, $numFileErrors);
        $genericErrors = \array_slice(['first generic error', 'second generic error'], 0, $numGenericErrors);
        return new \_PhpScoper0a2ac50786fa\PHPStan\Command\AnalysisResult($fileErrors, $genericErrors, [], [], \false, null, \true);
    }
    private function rtrimMultiline(string $output) : string
    {
        $result = \array_map(static function (string $line) : string {
            return \rtrim($line, " \r\n");
        }, \explode("\n", $output));
        return \implode("\n", $result);
    }
}
