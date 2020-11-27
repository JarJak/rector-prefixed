<?php

declare (strict_types=1);
namespace PHPStan\DependencyInjection;

use _PhpScoper006a73f0e455\Nette\DI\Config\Adapter;
use _PhpScoper006a73f0e455\Nette\DI\Config\Helpers;
use _PhpScoper006a73f0e455\Nette\DI\Definitions\Reference;
use _PhpScoper006a73f0e455\Nette\DI\Definitions\Statement;
use _PhpScoper006a73f0e455\Nette\Neon\Entity;
use _PhpScoper006a73f0e455\Nette\Neon\Neon;
use PHPStan\File\FileHelper;
use PHPStan\File\FileReader;
class NeonAdapter implements \_PhpScoper006a73f0e455\Nette\DI\Config\Adapter
{
    public const CACHE_KEY = 'v10';
    private const PREVENT_MERGING_SUFFIX = '!';
    /** @var FileHelper[] */
    private $fileHelpers = [];
    /**
     * @param string $file
     * @return mixed[]
     */
    public function load(string $file) : array
    {
        $contents = \PHPStan\File\FileReader::read($file);
        try {
            return $this->process((array) \_PhpScoper006a73f0e455\Nette\Neon\Neon::decode($contents), '', $file);
        } catch (\_PhpScoper006a73f0e455\Nette\Neon\Exception $e) {
            throw new \_PhpScoper006a73f0e455\Nette\Neon\Exception(\sprintf('Error while loading %s: %s', $file, $e->getMessage()));
        }
    }
    /**
     * @param mixed[] $arr
     * @return mixed[]
     */
    public function process(array $arr, string $fileKey, string $file) : array
    {
        $res = [];
        foreach ($arr as $key => $val) {
            if (\is_string($key) && \substr($key, -1) === self::PREVENT_MERGING_SUFFIX) {
                if (!\is_array($val) && $val !== null) {
                    throw new \_PhpScoper006a73f0e455\Nette\DI\InvalidConfigurationException(\sprintf('Replacing operator is available only for arrays, item \'%s\' is not array.', $key));
                }
                $key = \substr($key, 0, -1);
                $val[\_PhpScoper006a73f0e455\Nette\DI\Config\Helpers::PREVENT_MERGING] = \true;
            }
            if (\is_array($val)) {
                if (!\is_int($key)) {
                    $fileKeyToPass = $fileKey . '[' . $key . ']';
                } else {
                    $fileKeyToPass = $fileKey . '[]';
                }
                $val = $this->process($val, $fileKeyToPass, $file);
            } elseif ($val instanceof \_PhpScoper006a73f0e455\Nette\Neon\Entity) {
                if (!\is_int($key)) {
                    $fileKeyToPass = $fileKey . '(' . $key . ')';
                } else {
                    $fileKeyToPass = $fileKey . '()';
                }
                if ($val->value === \_PhpScoper006a73f0e455\Nette\Neon\Neon::CHAIN) {
                    $tmp = null;
                    foreach ($this->process($val->attributes, $fileKeyToPass, $file) as $st) {
                        $tmp = new \_PhpScoper006a73f0e455\Nette\DI\Definitions\Statement($tmp === null ? $st->getEntity() : [$tmp, \ltrim(\implode('::', (array) $st->getEntity()), ':')], $st->arguments);
                    }
                    $val = $tmp;
                } else {
                    $tmp = $this->process([$val->value], $fileKeyToPass, $file);
                    $val = new \_PhpScoper006a73f0e455\Nette\DI\Definitions\Statement($tmp[0], $this->process($val->attributes, $fileKeyToPass, $file));
                }
            }
            $keyToResolve = $fileKey;
            if (\is_int($key)) {
                $keyToResolve .= '[]';
            } else {
                $keyToResolve .= '[' . $key . ']';
            }
            if (\in_array($keyToResolve, ['[parameters][autoload_files][]', '[parameters][autoload_directories][]', '[parameters][paths][]', '[parameters][excludes_analyse][]', '[parameters][ignoreErrors][][paths][]', '[parameters][ignoreErrors][][path]', '[parameters][bootstrap]', '[parameters][bootstrapFiles][]', '[parameters][scanFiles][]', '[parameters][scanDirectories][]', '[parameters][tmpDir]', '[parameters][memoryLimitFile]', '[parameters][benchmarkFile]', '[parameters][stubFiles][]', '[parameters][symfony][console_application_loader]', '[parameters][symfony][container_xml_path]', '[parameters][doctrine][objectManagerLoader]'], \true) && \is_string($val) && \strpos($val, '%') === \false && \strpos($val, '*') !== 0) {
                $fileHelper = $this->createFileHelperByFile($file);
                $val = $fileHelper->normalizePath($fileHelper->absolutizePath($val));
            }
            $res[$key] = $val;
        }
        return $res;
    }
    /**
     * @param mixed[] $data
     * @return string
     */
    public function dump(array $data) : string
    {
        \array_walk_recursive($data, static function (&$val) : void {
            if (!$val instanceof \_PhpScoper006a73f0e455\Nette\DI\Definitions\Statement) {
                return;
            }
            $val = self::statementToEntity($val);
        });
        return "# generated by Nette\n\n" . \_PhpScoper006a73f0e455\Nette\Neon\Neon::encode($data, \_PhpScoper006a73f0e455\Nette\Neon\Neon::BLOCK);
    }
    private static function statementToEntity(\_PhpScoper006a73f0e455\Nette\DI\Definitions\Statement $val) : \_PhpScoper006a73f0e455\Nette\Neon\Entity
    {
        \array_walk_recursive($val->arguments, static function (&$val) : void {
            if ($val instanceof \_PhpScoper006a73f0e455\Nette\DI\Definitions\Statement) {
                $val = self::statementToEntity($val);
            } elseif ($val instanceof \_PhpScoper006a73f0e455\Nette\DI\Definitions\Reference) {
                $val = '@' . $val->getValue();
            }
        });
        $entity = $val->getEntity();
        if ($entity instanceof \_PhpScoper006a73f0e455\Nette\DI\Definitions\Reference) {
            $entity = '@' . $entity->getValue();
        } elseif (\is_array($entity)) {
            if ($entity[0] instanceof \_PhpScoper006a73f0e455\Nette\DI\Definitions\Statement) {
                return new \_PhpScoper006a73f0e455\Nette\Neon\Entity(\_PhpScoper006a73f0e455\Nette\Neon\Neon::CHAIN, [self::statementToEntity($entity[0]), new \_PhpScoper006a73f0e455\Nette\Neon\Entity('::' . $entity[1], $val->arguments)]);
            } elseif ($entity[0] instanceof \_PhpScoper006a73f0e455\Nette\DI\Definitions\Reference) {
                $entity = '@' . $entity[0]->getValue() . '::' . $entity[1];
            } elseif (\is_string($entity[0])) {
                $entity = $entity[0] . '::' . $entity[1];
            }
        }
        return new \_PhpScoper006a73f0e455\Nette\Neon\Entity($entity, $val->arguments);
    }
    private function createFileHelperByFile(string $file) : \PHPStan\File\FileHelper
    {
        $dir = \dirname($file);
        if (!isset($this->fileHelpers[$dir])) {
            $this->fileHelpers[$dir] = new \PHPStan\File\FileHelper($dir);
        }
        return $this->fileHelpers[$dir];
    }
}