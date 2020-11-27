<?php

declare (strict_types=1);
namespace Rector\CodingStyle\Node;

use _PhpScoper006a73f0e455\Nette\Utils\Strings;
use PhpParser\Node;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStan\Type\AliasedObjectType;
final class DocAliasResolver
{
    /**
     * @var string
     * @see https://regex101.com/r/cWpliJ/1
     */
    private const DOC_ALIAS_REGEX = '#\\@(?<possible_alias>\\w+)(\\\\)?#s';
    /**
     * @var CallableNodeTraverser
     */
    private $callableNodeTraverser;
    public function __construct(\Rector\Core\PhpParser\NodeTraverser\CallableNodeTraverser $callableNodeTraverser)
    {
        $this->callableNodeTraverser = $callableNodeTraverser;
    }
    /**
     * @return string[]
     */
    public function resolve(\PhpParser\Node $node) : array
    {
        $possibleDocAliases = [];
        $this->callableNodeTraverser->traverseNodesWithCallable($node, function (\PhpParser\Node $node) use(&$possibleDocAliases) : void {
            $docComment = $node->getDocComment();
            if ($docComment === null) {
                return;
            }
            /** @var PhpDocInfo $phpDocInfo */
            $phpDocInfo = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
            if ($phpDocInfo->getVarType()) {
                $possibleDocAliases = $this->collectVarType($phpDocInfo, $possibleDocAliases);
            }
            // e.g. "use Dotrine\ORM\Mapping as ORM" etc.
            $matches = \_PhpScoper006a73f0e455\Nette\Utils\Strings::matchAll($docComment->getText(), self::DOC_ALIAS_REGEX);
            foreach ($matches as $match) {
                $possibleDocAliases[] = $match['possible_alias'];
            }
        });
        return \array_unique($possibleDocAliases);
    }
    /**
     * @param string[] $possibleDocAliases
     * @return string[]
     */
    private function collectVarType(\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo $phpDocInfo, array $possibleDocAliases) : array
    {
        $possibleDocAliases = $this->appendPossibleAliases($phpDocInfo->getVarType(), $possibleDocAliases);
        $possibleDocAliases = $this->appendPossibleAliases($phpDocInfo->getReturnType(), $possibleDocAliases);
        foreach ($phpDocInfo->getParamTypes() as $paramType) {
            $possibleDocAliases = $this->appendPossibleAliases($paramType, $possibleDocAliases);
        }
        return $possibleDocAliases;
    }
    /**
     * @param string[] $possibleDocAliases
     * @return string[]
     */
    private function appendPossibleAliases(\PHPStan\Type\Type $varType, array $possibleDocAliases) : array
    {
        if ($varType instanceof \Rector\PHPStan\Type\AliasedObjectType) {
            $possibleDocAliases[] = $varType->getClassName();
        }
        if ($varType instanceof \PHPStan\Type\UnionType) {
            foreach ($varType->getTypes() as $type) {
                $possibleDocAliases = $this->appendPossibleAliases($type, $possibleDocAliases);
            }
        }
        return $possibleDocAliases;
    }
}