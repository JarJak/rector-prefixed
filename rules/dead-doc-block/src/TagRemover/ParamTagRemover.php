<?php

declare (strict_types=1);
namespace Rector\DeadDocBlock\TagRemover;

use PhpParser\Node\FunctionLike;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\DeadDocBlock\DeadParamTagValueNodeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
final class ParamTagRemover
{
    /**
     * @var DeadParamTagValueNodeAnalyzer
     */
    private $deadParamTagValueNodeAnalyzer;
    public function __construct(\Rector\DeadDocBlock\DeadParamTagValueNodeAnalyzer $deadParamTagValueNodeAnalyzer)
    {
        $this->deadParamTagValueNodeAnalyzer = $deadParamTagValueNodeAnalyzer;
    }
    public function removeParamTagsIfUseless(\PhpParser\Node\FunctionLike $functionLike) : void
    {
        $phpDocInfo = $functionLike->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if (!$phpDocInfo instanceof \Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo) {
            return;
        }
        foreach ($phpDocInfo->getParamTagValueNodes() as $paramTagValueNode) {
            $paramName = $paramTagValueNode->parameterName;
            // remove existing type
            $paramTagValueNode = $phpDocInfo->getParamTagValueByName($paramName);
            if ($paramTagValueNode === null) {
                continue;
            }
            $isParamTagValueDead = $this->deadParamTagValueNodeAnalyzer->isDead($paramTagValueNode, $functionLike);
            if (!$isParamTagValueDead) {
                continue;
            }
            $phpDocInfo->removeTagValueNodeFromNode($paramTagValueNode);
        }
    }
}
