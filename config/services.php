<?php

declare (strict_types=1);
namespace _PhpScoper26e51eeacccf;

use _PhpScoper26e51eeacccf\Doctrine\Inflector\Inflector;
use _PhpScoper26e51eeacccf\Doctrine\Inflector\Rules\English\InflectorFactory;
use _PhpScoper26e51eeacccf\OndraM\CiDetector\CiDetector;
use PhpParser\BuilderFactory;
use PhpParser\Lexer;
use PhpParser\NodeFinder;
use PhpParser\NodeVisitor\CloningVisitor;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Rector\Core\Bootstrap\NoRectorsLoadedReporter;
use Rector\Core\Configuration\RectorClassesProvider;
use Rector\Core\Console\ConsoleApplication;
use Rector\Core\EventDispatcher\AutowiredEventDispatcher;
use Rector\Core\PhpParser\Parser\NikicPhpParserFactory;
use Rector\Core\PhpParser\Parser\PhpParserLexerFactory;
use _PhpScoper26e51eeacccf\Symfony\Component\Console\Application as SymfonyApplication;
use _PhpScoper26e51eeacccf\Symfony\Component\Console\Descriptor\TextDescriptor;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function _PhpScoper26e51eeacccf\Symfony\Component\DependencyInjection\Loader\Configurator\ref;
use _PhpScoper26e51eeacccf\Symfony\Component\EventDispatcher\EventDispatcherInterface;
use _PhpScoper26e51eeacccf\Symfony\Component\Filesystem\Filesystem;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\PackageBuilder\Reflection\PrivatesCaller;
use Symplify\PackageBuilder\Strings\StringFormatConverter;
use Symplify\SmartFileSystem\FileSystemFilter;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\SmartFileSystem;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('Rector\\Core\\', __DIR__ . '/../src')->exclude([
        __DIR__ . '/../src/Rector',
        __DIR__ . '/../src/Exception',
        __DIR__ . '/../src/DependencyInjection/CompilerPass',
        __DIR__ . '/../src/DependencyInjection/Loader',
        __DIR__ . '/../src/PhpParser/Builder',
        __DIR__ . '/../src/HttpKernel',
        __DIR__ . '/../src/ValueObject',
        __DIR__ . '/../src/Bootstrap',
        __DIR__ . '/../src/PhpParser/Node/CustomNode',
        // loaded for PHPStan factory
        __DIR__ . '/../src/PHPStan/Type',
    ]);
    $services->alias(\_PhpScoper26e51eeacccf\Symfony\Component\Console\Application::class, \Rector\Core\Console\ConsoleApplication::class);
    $services->set(\Rector\Core\Bootstrap\NoRectorsLoadedReporter::class);
    $services->set(\_PhpScoper26e51eeacccf\Symfony\Component\Console\Descriptor\TextDescriptor::class);
    $services->set(\PhpParser\ParserFactory::class);
    $services->set(\PhpParser\BuilderFactory::class);
    $services->set(\PhpParser\NodeVisitor\CloningVisitor::class);
    $services->set(\PhpParser\NodeFinder::class);
    $services->set(\PhpParser\Parser::class)->factory([\_PhpScoper26e51eeacccf\Symfony\Component\DependencyInjection\Loader\Configurator\ref(\Rector\Core\PhpParser\Parser\NikicPhpParserFactory::class), 'create']);
    $services->set(\PhpParser\Lexer::class)->factory([\_PhpScoper26e51eeacccf\Symfony\Component\DependencyInjection\Loader\Configurator\ref(\Rector\Core\PhpParser\Parser\PhpParserLexerFactory::class), 'create']);
    // symplify/package-builder
    $services->set(\_PhpScoper26e51eeacccf\Symfony\Component\Filesystem\Filesystem::class);
    $services->set(\Symplify\PackageBuilder\Reflection\PrivatesAccessor::class);
    $services->set(\Symplify\PackageBuilder\Reflection\PrivatesCaller::class);
    $services->set(\Symplify\SmartFileSystem\Finder\FinderSanitizer::class);
    $services->set(\Symplify\SmartFileSystem\FileSystemFilter::class);
    $services->set(\Symplify\PackageBuilder\Parameter\ParameterProvider::class);
    $services->set(\Symplify\PackageBuilder\Parameter\ParameterProvider::class)->arg('$container', \_PhpScoper26e51eeacccf\Symfony\Component\DependencyInjection\Loader\Configurator\ref('service_container'));
    $services->set(\Rector\Core\Configuration\RectorClassesProvider::class)->arg('$container', \_PhpScoper26e51eeacccf\Symfony\Component\DependencyInjection\Loader\Configurator\ref('service_container'));
    $services->set(\Symplify\PackageBuilder\Console\Command\CommandNaming::class);
    $services->set(\Symplify\SmartFileSystem\SmartFileSystem::class);
    $services->set(\Symplify\PackageBuilder\Strings\StringFormatConverter::class);
    $services->set(\_PhpScoper26e51eeacccf\OndraM\CiDetector\CiDetector::class);
    $services->alias(\_PhpScoper26e51eeacccf\Symfony\Component\EventDispatcher\EventDispatcherInterface::class, \Rector\Core\EventDispatcher\AutowiredEventDispatcher::class);
    $services->set(\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class);
    $services->set(\Symfony\Component\Console\Style\SymfonyStyle::class)->factory([\_PhpScoper26e51eeacccf\Symfony\Component\DependencyInjection\Loader\Configurator\ref(\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory::class), 'create']);
    $services->set(\_PhpScoper26e51eeacccf\Doctrine\Inflector\Rules\English\InflectorFactory::class);
    $services->set(\_PhpScoper26e51eeacccf\Doctrine\Inflector\Inflector::class)->factory([\_PhpScoper26e51eeacccf\Symfony\Component\DependencyInjection\Loader\Configurator\ref(\_PhpScoper26e51eeacccf\Doctrine\Inflector\Rules\English\InflectorFactory::class), 'build']);
};
