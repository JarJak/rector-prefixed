<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\AnnotationReader;

use _PhpScoper0a2ac50786fa\Doctrine\Common\Annotations\AnnotationReader;
use _PhpScoper0a2ac50786fa\Doctrine\Common\Annotations\AnnotationRegistry;
use _PhpScoper0a2ac50786fa\Doctrine\Common\Annotations\DocParser;
use _PhpScoper0a2ac50786fa\Doctrine\Common\Annotations\Reader;
use _PhpScoper0a2ac50786fa\Rector\DoctrineAnnotationGenerated\ConstantPreservingAnnotationReader;
use _PhpScoper0a2ac50786fa\Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser;
final class AnnotationReaderFactory
{
    /**
     * @var string[]
     */
    private const IGNORED_NAMES = [
        '_PhpScoper0a2ac50786fa\\ORM\\GeneratedValue',
        'GeneratedValue',
        '_PhpScoper0a2ac50786fa\\ORM\\InheritanceType',
        'InheritanceType',
        '_PhpScoper0a2ac50786fa\\ORM\\OrderBy',
        'OrderBy',
        '_PhpScoper0a2ac50786fa\\ORM\\DiscriminatorMap',
        'DiscriminatorMap',
        '_PhpScoper0a2ac50786fa\\ORM\\UniqueEntity',
        'UniqueEntity',
        '_PhpScoper0a2ac50786fa\\Gedmo\\SoftDeleteable',
        'SoftDeleteable',
        '_PhpScoper0a2ac50786fa\\Gedmo\\Slug',
        'Slug',
        '_PhpScoper0a2ac50786fa\\Gedmo\\SoftDeleteable',
        'SoftDeleteable',
        '_PhpScoper0a2ac50786fa\\Gedmo\\Blameable',
        'Blameable',
        '_PhpScoper0a2ac50786fa\\Gedmo\\Versioned',
        'Versioned',
        // nette @inject dummy annotation
        'inject',
    ];
    public function create() : \_PhpScoper0a2ac50786fa\Doctrine\Common\Annotations\Reader
    {
        \_PhpScoper0a2ac50786fa\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
        // generated
        $annotationReader = $this->createAnnotationReader();
        // without this the reader will try to resolve them and fails with an exception
        // don't forget to add it to "stubs/Doctrine/Empty" directory, because the class needs to exists
        // and run "composer dump-autoload", because the directory is loaded by classmap
        foreach (self::IGNORED_NAMES as $ignoredName) {
            $annotationReader::addGlobalIgnoredName($ignoredName);
        }
        // warning: nested tags must be parse-able, e.g. @ORM\Table must include @ORM\UniqueConstraint!
        return $annotationReader;
    }
    /**
     * @return AnnotationReader|ConstantPreservingAnnotationReader
     */
    private function createAnnotationReader() : \_PhpScoper0a2ac50786fa\Doctrine\Common\Annotations\Reader
    {
        // these 2 classes are generated by "bin/rector sync-annotation-parser" command
        if (\class_exists(\_PhpScoper0a2ac50786fa\Rector\DoctrineAnnotationGenerated\ConstantPreservingAnnotationReader::class) && \class_exists(\_PhpScoper0a2ac50786fa\Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser::class)) {
            $constantPreservingDocParser = new \_PhpScoper0a2ac50786fa\Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser();
            return new \_PhpScoper0a2ac50786fa\Rector\DoctrineAnnotationGenerated\ConstantPreservingAnnotationReader($constantPreservingDocParser);
        }
        // fallback for testing incompatibilities
        return new \_PhpScoper0a2ac50786fa\Doctrine\Common\Annotations\AnnotationReader(new \_PhpScoper0a2ac50786fa\Doctrine\Common\Annotations\DocParser());
    }
}
