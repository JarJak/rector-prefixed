<?php

declare (strict_types=1);
namespace Rector\SOLID\Rector\Class_;

use _PhpScoper006a73f0e455\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use Rector\Core\Php\ReservedKeywordAnalyzer;
use Rector\Core\PhpParser\Node\Manipulator\ClassInsertManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Util\StaticRectorStrings;
use Rector\NodeNestingScope\NodeFinder\ScopeAwareNodeFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\SOLID\Tests\Rector\Class_\RepeatedLiteralToClassConstantRector\RepeatedLiteralToClassConstantRectorTest
 */
final class RepeatedLiteralToClassConstantRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const VALUE = 'value';
    /**
     * @var string
     */
    private const NUMBERS = 'numbers';
    /**
     * @var string
     */
    private const UNDERSCORE = '_';
    /**
     * @var int
     */
    private const MINIMAL_VALUE_OCCURRENCE = 3;
    /**
     * @var string
     * @see https://regex101.com/r/osJLMF/1
     */
    private const SLASH_AND_DASH_REGEX = '#[-\\\\/]#';
    /**
     * @var ClassInsertManipulator
     */
    private $classInsertManipulator;
    /**
     * @var ScopeAwareNodeFinder
     */
    private $scopeAwareNodeFinder;
    /**
     * @var ReservedKeywordAnalyzer
     */
    private $reservedKeywordAnalyzer;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\ClassInsertManipulator $classInsertManipulator, \Rector\Core\Php\ReservedKeywordAnalyzer $reservedKeywordAnalyzer, \Rector\NodeNestingScope\NodeFinder\ScopeAwareNodeFinder $scopeAwareNodeFinder)
    {
        $this->classInsertManipulator = $classInsertManipulator;
        $this->scopeAwareNodeFinder = $scopeAwareNodeFinder;
        $this->reservedKeywordAnalyzer = $reservedKeywordAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Replace repeated strings with constant', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run($key, $items)
    {
        if ($key === 'requires') {
            return $items['requires'];
        }
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var string
     */
    private const REQUIRES = 'requires';
    public function run($key, $items)
    {
        if ($key === self::REQUIRES) {
            return $items[self::REQUIRES];
        }
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        // skip tests, where string values are often used as fixtures
        if ($this->isName($node, '*Test')) {
            return null;
        }
        /** @var String_[] $strings */
        $strings = $this->betterNodeFinder->findInstanceOf($node, \PhpParser\Node\Scalar\String_::class);
        $stringsToReplace = $this->resolveStringsToReplace($strings);
        if ($stringsToReplace === []) {
            return null;
        }
        $this->replaceStringsWithClassConstReferences($node, $stringsToReplace);
        $this->addClassConsts($stringsToReplace, $node);
        return $node;
    }
    /**
     * @param String_[] $strings
     * @return string[]
     */
    private function resolveStringsToReplace(array $strings) : array
    {
        $stringsByValue = [];
        foreach ($strings as $string) {
            if ($this->shouldSkipString($string)) {
                continue;
            }
            $stringsByValue[(string) $string->value][] = $string;
        }
        $stringsToReplace = [];
        foreach ($stringsByValue as $value => $strings) {
            if (\count($strings) < self::MINIMAL_VALUE_OCCURRENCE) {
                continue;
            }
            $stringsToReplace[] = (string) $value;
        }
        return $stringsToReplace;
    }
    /**
     * @param string[] $stringsToReplace
     */
    private function replaceStringsWithClassConstReferences(\PhpParser\Node\Stmt\Class_ $class, array $stringsToReplace) : void
    {
        $this->traverseNodesWithCallable($class, function (\PhpParser\Node $node) use($stringsToReplace) : ?ClassConstFetch {
            if (!$node instanceof \PhpParser\Node\Scalar\String_) {
                return null;
            }
            if (!$this->isValues($node, $stringsToReplace)) {
                return null;
            }
            $constantName = $this->createConstName($node->value);
            return new \PhpParser\Node\Expr\ClassConstFetch(new \PhpParser\Node\Name('self'), $constantName);
        });
    }
    /**
     * @param string[] $stringsToReplace
     */
    private function addClassConsts(array $stringsToReplace, \PhpParser\Node\Stmt\Class_ $class) : void
    {
        foreach ($stringsToReplace as $stringToReplace) {
            $constantName = $this->createConstName($stringToReplace);
            $classConst = $this->nodeFactory->createPrivateClassConst($constantName, new \PhpParser\Node\Scalar\String_($stringToReplace));
            $this->classInsertManipulator->addConstantToClass($class, $stringToReplace, $classConst);
        }
    }
    private function shouldSkipString(\PhpParser\Node\Scalar\String_ $string) : bool
    {
        $value = (string) $string->value;
        // value is too short
        if (\strlen($value) < 2) {
            return \true;
        }
        if ($this->reservedKeywordAnalyzer->isReserved($value)) {
            return \true;
        }
        if ($this->isNativeConstantResemblingValue($value)) {
            return \true;
        }
        // is replaceable value?
        $matches = \_PhpScoper006a73f0e455\Nette\Utils\Strings::match($value, '#(?<' . self::VALUE . '>[\\w\\-\\/\\_]+)#');
        if (!isset($matches[self::VALUE])) {
            return \true;
        }
        // skip values in another constants
        $parentConst = $this->scopeAwareNodeFinder->findParentType($string, [\PhpParser\Node\Stmt\ClassConst::class]);
        if ($parentConst !== null) {
            return \true;
        }
        return $matches[self::VALUE] !== (string) $string->value;
    }
    private function createConstName(string $value) : string
    {
        // replace slashes and dashes
        $value = \_PhpScoper006a73f0e455\Nette\Utils\Strings::replace($value, self::SLASH_AND_DASH_REGEX, self::UNDERSCORE);
        // find beginning numbers
        $beginningNumbers = '';
        $matches = \_PhpScoper006a73f0e455\Nette\Utils\Strings::match($value, '#(?<' . self::NUMBERS . '>[0-9]*)(?<' . self::VALUE . '>.*)#');
        if (isset($matches[self::NUMBERS])) {
            $beginningNumbers = $matches[self::NUMBERS];
        }
        if (isset($matches[self::VALUE])) {
            $value = $matches[self::VALUE];
        }
        // convert camelcase parts to underscore
        $parts = \array_map(function (string $v) : string {
            return \Rector\Core\Util\StaticRectorStrings::camelCaseToUnderscore($v);
        }, \explode(self::UNDERSCORE, $value));
        // apply "CONST" prefix if constant beginning with number
        if ($beginningNumbers !== '') {
            $parts = \array_merge(['CONST', $beginningNumbers], $parts);
        }
        $value = \implode(self::UNDERSCORE, $parts);
        return \strtoupper(\_PhpScoper006a73f0e455\Nette\Utils\Strings::replace($value, '#_+#', self::UNDERSCORE));
    }
    private function isNativeConstantResemblingValue(string $value) : bool
    {
        $loweredValue = \strtolower($value);
        return \in_array($loweredValue, ['true', 'false', 'bool', 'null'], \true);
    }
}