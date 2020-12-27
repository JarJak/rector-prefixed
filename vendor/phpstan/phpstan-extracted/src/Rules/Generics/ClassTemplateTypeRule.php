<?php

declare (strict_types=1);
namespace RectorPrefix20201227\PHPStan\Rules\Generics;

use PhpParser\Node;
use RectorPrefix20201227\PHPStan\Analyser\Scope;
use RectorPrefix20201227\PHPStan\Node\InClassNode;
use RectorPrefix20201227\PHPStan\Rules\Rule;
use PHPStan\Type\Generic\TemplateTypeScope;
/**
 * @implements \PHPStan\Rules\Rule<InClassNode>
 */
class ClassTemplateTypeRule implements \RectorPrefix20201227\PHPStan\Rules\Rule
{
    /** @var \PHPStan\Rules\Generics\TemplateTypeCheck */
    private $templateTypeCheck;
    public function __construct(\RectorPrefix20201227\PHPStan\Rules\Generics\TemplateTypeCheck $templateTypeCheck)
    {
        $this->templateTypeCheck = $templateTypeCheck;
    }
    public function getNodeType() : string
    {
        return \RectorPrefix20201227\PHPStan\Node\InClassNode::class;
    }
    public function processNode(\PhpParser\Node $node, \RectorPrefix20201227\PHPStan\Analyser\Scope $scope) : array
    {
        if (!$scope->isInClass()) {
            return [];
        }
        $classReflection = $scope->getClassReflection();
        $className = $classReflection->getName();
        if ($classReflection->isAnonymous()) {
            $displayName = 'anonymous class';
        } else {
            $displayName = 'class ' . $classReflection->getDisplayName();
        }
        return $this->templateTypeCheck->check($node, \PHPStan\Type\Generic\TemplateTypeScope::createWithClass($className), $classReflection->getTemplateTags(), \sprintf('PHPDoc tag @template for %s cannot have existing class %%s as its name.', $displayName), \sprintf('PHPDoc tag @template for %s cannot have existing type alias %%s as its name.', $displayName), \sprintf('PHPDoc tag @template %%s for %s has invalid bound type %%s.', $displayName), \sprintf('PHPDoc tag @template %%s for %s with bound type %%s is not supported.', $displayName));
    }
}
