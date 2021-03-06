<?php

declare (strict_types=1);
namespace Rector\Core\Application;

use Rector\Core\ValueObject\Application\ParsedStmtsAndTokens;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
final class TokensByFilePathStorage
{
    /**
     * @var ParsedStmtsAndTokens[]
     */
    private $tokensByFilePath = [];
    public function addForRealPath(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo, \Rector\Core\ValueObject\Application\ParsedStmtsAndTokens $parsedStmtsAndTokens) : void
    {
        $this->tokensByFilePath[$smartFileInfo->getRealPath()] = $parsedStmtsAndTokens;
    }
    public function hasForFileInfo(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : bool
    {
        return isset($this->tokensByFilePath[$smartFileInfo->getRealPath()]);
    }
    public function getForFileInfo(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : \Rector\Core\ValueObject\Application\ParsedStmtsAndTokens
    {
        return $this->tokensByFilePath[$smartFileInfo->getRealPath()];
    }
}
