<?php

declare (strict_types=1);
namespace Rector\Utils\PHPStanStaticTypeMapperChecker\Command;

use PHPStan\Type\NonexistentParentClassType;
use PHPStan\Type\ParserNodeTypeToPHPStanType;
use Rector\Core\Console\Command\AbstractCommand;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\Utils\PHPStanStaticTypeMapperChecker\Finder\PHPStanTypeClassFinder;
use _PhpScoper006a73f0e455\Symfony\Component\Console\Input\InputInterface;
use _PhpScoper006a73f0e455\Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;
final class CheckStaticTypeMappersCommand extends \Rector\Core\Console\Command\AbstractCommand
{
    /**
     * @var TypeMapperInterface[]
     */
    private $typeMappers = [];
    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;
    /**
     * @var PHPStanTypeClassFinder
     */
    private $phpStanTypeClassFinder;
    /**
     * @param TypeMapperInterface[] $typeMappers
     */
    public function __construct(array $typeMappers, \Symfony\Component\Console\Style\SymfonyStyle $symfonyStyle, \Rector\Utils\PHPStanStaticTypeMapperChecker\Finder\PHPStanTypeClassFinder $phpStanTypeClassFinder)
    {
        $this->typeMappers = $typeMappers;
        $this->symfonyStyle = $symfonyStyle;
        $this->phpStanTypeClassFinder = $phpStanTypeClassFinder;
        parent::__construct();
    }
    protected function configure() : void
    {
        $this->setDescription('[DEV] check PHPStan types to TypeMappers');
    }
    protected function execute(\_PhpScoper006a73f0e455\Symfony\Component\Console\Input\InputInterface $input, \_PhpScoper006a73f0e455\Symfony\Component\Console\Output\OutputInterface $output) : int
    {
        $missingNodeClasses = $this->getMissingNodeClasses();
        if ($missingNodeClasses === []) {
            $this->symfonyStyle->success('All PHPStan Types are covered by TypeMapper');
            return \Symplify\PackageBuilder\Console\ShellCode::SUCCESS;
        }
        foreach ($missingNodeClasses as $missingNodeClass) {
            $errorMessage = \sprintf('Add new class to "%s" that implements "%s" for "%s" type', 'packages/phpstan-static-type-mapper/src/TypeMapper', \Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface::class, $missingNodeClass);
            $this->symfonyStyle->error($errorMessage);
        }
        return \Symplify\PackageBuilder\Console\ShellCode::ERROR;
    }
    /**
     * @return class-string[]
     */
    private function getMissingNodeClasses() : array
    {
        $phpStanTypeClasses = $this->phpStanTypeClassFinder->find();
        $supportedTypeClasses = $this->getSupportedTypeClasses();
        $unsupportedTypeClasses = [];
        foreach ($phpStanTypeClasses as $phpStanTypeClass) {
            foreach ($supportedTypeClasses as $supportedPHPStanTypeClass) {
                if (\is_a($phpStanTypeClass, $supportedPHPStanTypeClass, \true)) {
                    continue 2;
                }
            }
            $unsupportedTypeClasses[] = $phpStanTypeClass;
        }
        $typesToRemove = [\PHPStan\Type\NonexistentParentClassType::class, \PHPStan\Type\ParserNodeTypeToPHPStanType::class];
        return \array_diff($unsupportedTypeClasses, $typesToRemove);
    }
    /**
     * @return string[]
     */
    private function getSupportedTypeClasses() : array
    {
        $supportedPHPStanTypeClasses = [];
        foreach ($this->typeMappers as $typeMappers) {
            $supportedPHPStanTypeClasses[] = $typeMappers->getNodeClass();
        }
        return $supportedPHPStanTypeClasses;
    }
}