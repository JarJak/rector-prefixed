<?php

declare (strict_types=1);
namespace Rector\NetteCodeQuality\Tests\Rector\ArrayDimFetch\ChangeControlArrayAccessToAnnotatedControlVariableRector\Source;

use RectorPrefix20201226\Nette\Application\UI\Control;
final class SomeControl extends \RectorPrefix20201226\Nette\Application\UI\Control
{
    public function callThis()
    {
    }
}
