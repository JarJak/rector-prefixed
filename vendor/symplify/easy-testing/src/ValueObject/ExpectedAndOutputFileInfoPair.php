<?php

declare (strict_types=1);
namespace RectorPrefix20210118\Symplify\EasyTesting\ValueObject;

use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
use RectorPrefix20210118\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
final class ExpectedAndOutputFileInfoPair
{
    /**
     * @var SmartFileInfo
     */
    private $expectedFileInfo;
    /**
     * @var SmartFileInfo|null
     */
    private $outputFileInfo;
    public function __construct(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $expectedFileInfo, ?\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $outputFileInfo)
    {
        $this->expectedFileInfo = $expectedFileInfo;
        $this->outputFileInfo = $outputFileInfo;
    }
    /**
     * @noRector \Rector\Privatization\Rector\ClassMethod\PrivatizeLocalOnlyMethodRector
     */
    public function getExpectedFileContent() : string
    {
        return $this->expectedFileInfo->getContents();
    }
    /**
     * @noRector \Rector\Privatization\Rector\ClassMethod\PrivatizeLocalOnlyMethodRector
     */
    public function getOutputFileContent() : string
    {
        if ($this->outputFileInfo === null) {
            throw new \RectorPrefix20210118\Symplify\SymplifyKernel\Exception\ShouldNotHappenException();
        }
        return $this->outputFileInfo->getContents();
    }
    /**
     * @noRector \Rector\Privatization\Rector\ClassMethod\PrivatizeLocalOnlyMethodRector
     */
    public function doesOutputFileExist() : bool
    {
        return $this->outputFileInfo !== null;
    }
}
