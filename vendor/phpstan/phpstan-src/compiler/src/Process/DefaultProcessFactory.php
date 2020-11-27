<?php

declare (strict_types=1);
namespace PHPStan\Compiler\Process;

use _PhpScopera143bcca66cb\Symfony\Component\Console\Output\NullOutput;
use _PhpScopera143bcca66cb\Symfony\Component\Console\Output\OutputInterface;
final class DefaultProcessFactory implements \PHPStan\Compiler\Process\ProcessFactory
{
    /** @var OutputInterface */
    private $output;
    public function __construct()
    {
        $this->output = new \_PhpScopera143bcca66cb\Symfony\Component\Console\Output\NullOutput();
    }
    /**
     * @param string[] $command
     * @param string $cwd
     * @return \PHPStan\Compiler\Process\Process
     */
    public function create(array $command, string $cwd) : \PHPStan\Compiler\Process\Process
    {
        return new \PHPStan\Compiler\Process\SymfonyProcess($command, $cwd, $this->output);
    }
    public function setOutput(\_PhpScopera143bcca66cb\Symfony\Component\Console\Output\OutputInterface $output) : void
    {
        $this->output = $output;
    }
}
