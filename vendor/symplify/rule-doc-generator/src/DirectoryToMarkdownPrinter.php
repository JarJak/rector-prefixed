<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator;

use _PhpScoper0a2ac50786fa\Symfony\Component\Console\Style\SymfonyStyle;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\Finder\ClassByTypeFinder;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\Printer\RuleDefinitionsPrinter;
/**
 * @see \Symplify\RuleDocGenerator\Tests\DirectoryToMarkdownPrinter\DirectoryToMarkdownPrinterTest
 */
final class DirectoryToMarkdownPrinter
{
    /**
     * @var ClassByTypeFinder
     */
    private $classByTypeFinder;
    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;
    /**
     * @var RuleDefinitionsResolver
     */
    private $ruleDefinitionsResolver;
    /**
     * @var RuleDefinitionsPrinter
     */
    private $ruleDefinitionsPrinter;
    public function __construct(\_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\Finder\ClassByTypeFinder $classByTypeFinder, \_PhpScoper0a2ac50786fa\Symfony\Component\Console\Style\SymfonyStyle $symfonyStyle, \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\RuleDefinitionsResolver $ruleDefinitionsResolver, \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\Printer\RuleDefinitionsPrinter $ruleDefinitionsPrinter)
    {
        $this->classByTypeFinder = $classByTypeFinder;
        $this->symfonyStyle = $symfonyStyle;
        $this->ruleDefinitionsResolver = $ruleDefinitionsResolver;
        $this->ruleDefinitionsPrinter = $ruleDefinitionsPrinter;
    }
    /**
     * @param string[] $directories
     */
    public function print(array $directories, bool $shouldCategorize = \false) : string
    {
        // 1. collect documented rules in provided path
        $documentedRuleClasses = $this->classByTypeFinder->findByType($directories, \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface::class);
        $message = \sprintf('Found %d documented rule classes', \count($documentedRuleClasses));
        $this->symfonyStyle->note($message);
        $this->symfonyStyle->listing($documentedRuleClasses);
        // 2. create rule definition collection
        $ruleDefinitions = $this->ruleDefinitionsResolver->resolveFromClassNames($documentedRuleClasses);
        // 3. print rule definitions to markdown lines
        $markdownLines = $this->ruleDefinitionsPrinter->print($ruleDefinitions, $shouldCategorize);
        $fileContent = '';
        foreach ($markdownLines as $markdownLine) {
            $fileContent .= \trim($markdownLine) . \PHP_EOL . \PHP_EOL;
        }
        return \rtrim($fileContent) . \PHP_EOL;
    }
}
