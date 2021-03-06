<?php

declare (strict_types=1);
namespace Rector\Transform\Tests\Rector\StaticCall\StaticCallToMethodCallRector\Source;

use RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileSystem;
abstract class ClassWithFileSystemMethod
{
    public function getSmartFileSystem() : \RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileSystem
    {
        return new \RectorPrefix20210118\Symplify\SmartFileSystem\SmartFileSystem();
    }
}
