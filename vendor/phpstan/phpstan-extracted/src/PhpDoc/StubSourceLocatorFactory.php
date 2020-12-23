<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\PhpDoc;

use _PhpScoper0a2ac50786fa\PHPStan\DependencyInjection\Container;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedSingleFileSourceLocatorRepository;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflector\FunctionReflector;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Ast\Locator;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\MemoizingSourceLocator;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\PhpInternalSourceLocator;
use _PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\SourceLocator;
class StubSourceLocatorFactory
{
    /** @var \PhpParser\Parser */
    private $parser;
    /** @var PhpStormStubsSourceStubber */
    private $phpStormStubsSourceStubber;
    /** @var \PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedSingleFileSourceLocatorRepository */
    private $optimizedSingleFileSourceLocatorRepository;
    /** @var \PHPStan\DependencyInjection\Container */
    private $container;
    /** @var string[] */
    private $stubFiles;
    /**
     * @param string[] $stubFiles
     */
    public function __construct(\_PhpScoper0a2ac50786fa\PhpParser\Parser $parser, \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\SourceStubber\PhpStormStubsSourceStubber $phpStormStubsSourceStubber, \_PhpScoper0a2ac50786fa\PHPStan\Reflection\BetterReflection\SourceLocator\OptimizedSingleFileSourceLocatorRepository $optimizedSingleFileSourceLocatorRepository, \_PhpScoper0a2ac50786fa\PHPStan\DependencyInjection\Container $container, array $stubFiles)
    {
        $this->parser = $parser;
        $this->phpStormStubsSourceStubber = $phpStormStubsSourceStubber;
        $this->optimizedSingleFileSourceLocatorRepository = $optimizedSingleFileSourceLocatorRepository;
        $this->container = $container;
        $this->stubFiles = $stubFiles;
    }
    public function create() : \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\SourceLocator
    {
        $locators = [];
        $astLocator = new \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Ast\Locator($this->parser, function () : FunctionReflector {
            return $this->container->getService('stubFunctionReflector');
        });
        foreach ($this->stubFiles as $stubFile) {
            $locators[] = $this->optimizedSingleFileSourceLocatorRepository->getOrCreate($stubFile);
        }
        $locators[] = new \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\PhpInternalSourceLocator($astLocator, $this->phpStormStubsSourceStubber);
        return new \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\MemoizingSourceLocator(new \_PhpScoper0a2ac50786fa\_HumbugBox221ad6f1b81f\Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator($locators));
    }
}
