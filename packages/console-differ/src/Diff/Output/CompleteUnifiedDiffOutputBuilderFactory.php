<?php

declare (strict_types=1);
namespace Rector\ConsoleDiffer\Diff\Output;

use _PhpScoperabd03f0baf05\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
/**
 * Creates @see UnifiedDiffOutputBuilder with "$contextLines = 1000;"
 */
final class CompleteUnifiedDiffOutputBuilderFactory
{
    /**
     * @var PrivatesAccessor
     */
    private $privatesAccessor;
    public function __construct()
    {
        $this->privatesAccessor = new \Symplify\PackageBuilder\Reflection\PrivatesAccessor();
    }
    /**
     * @api
     */
    public function create() : \_PhpScoperabd03f0baf05\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder
    {
        $unifiedDiffOutputBuilder = new \_PhpScoperabd03f0baf05\SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder('');
        $this->privatesAccessor->setPrivateProperty($unifiedDiffOutputBuilder, 'contextLines', 1000);
        return $unifiedDiffOutputBuilder;
    }
}
