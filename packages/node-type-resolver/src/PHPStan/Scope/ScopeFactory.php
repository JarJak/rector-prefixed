<?php

declare (strict_types=1);
namespace Rector\NodeTypeResolver\PHPStan\Scope;

use PHPStan\Analyser\Scope;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory as PHPStanScopeFactory;
use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo;
final class ScopeFactory
{
    /**
     * @var PHPStanScopeFactory
     */
    private $phpStanScopeFactory;
    public function __construct(\PHPStan\Analyser\ScopeFactory $phpStanScopeFactory)
    {
        $this->phpStanScopeFactory = $phpStanScopeFactory;
    }
    public function createFromFile(\RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : \PHPStan\Analyser\Scope
    {
        return $this->phpStanScopeFactory->create(\PHPStan\Analyser\ScopeContext::create($fileInfo->getRealPath()));
    }
}
