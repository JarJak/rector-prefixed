<?php

declare (strict_types=1);
namespace Rector\Privatization\Rector\ClassMethod;

use RectorPrefix20210118\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Caching\Contract\Rector\ZeroCacheRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpAttribute\ValueObject\TagName;
use Rector\Privatization\NodeAnalyzer\ClassMethodExternalCallNodeAnalyzer;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodVisibilityVendorLockResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Privatization\Tests\Rector\ClassMethod\PrivatizeLocalOnlyMethodRector\PrivatizeLocalOnlyMethodRectorTest
 */
final class PrivatizeLocalOnlyMethodRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Caching\Contract\Rector\ZeroCacheRectorInterface
{
    /**
     * @var string
     * @see https://regex101.com/r/f97wwM/1
     */
    private const COMMON_PUBLIC_METHOD_CONTROLLER_REGEX = '#^(render|action|handle|inject)#';
    /**
     * @var string
     * @see https://regex101.com/r/FXhI9M/1
     */
    private const CONTROLLER_PRESENTER_SUFFIX_REGEX = '#(Controller|Presenter)$#';
    /**
     * @var string
     */
    private const API = 'api';
    /**
     * @var ClassMethodVisibilityVendorLockResolver
     */
    private $classMethodVisibilityVendorLockResolver;
    /**
     * @var ClassMethodExternalCallNodeAnalyzer
     */
    private $classMethodExternalCallNodeAnalyzer;
    public function __construct(\Rector\Privatization\NodeAnalyzer\ClassMethodExternalCallNodeAnalyzer $classMethodExternalCallNodeAnalyzer, \Rector\VendorLocker\NodeVendorLocker\ClassMethodVisibilityVendorLockResolver $classMethodVisibilityVendorLockResolver)
    {
        $this->classMethodVisibilityVendorLockResolver = $classMethodVisibilityVendorLockResolver;
        $this->classMethodExternalCallNodeAnalyzer = $classMethodExternalCallNodeAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Privatize local-only use methods', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @api
     */
    public function run()
    {
        return $this->useMe();
    }

    public function useMe()
    {
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @api
     */
    public function run()
    {
        return $this->useMe();
    }

    private function useMe()
    {
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
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        if ($this->classMethodExternalCallNodeAnalyzer->hasExternalCall($node)) {
            return null;
        }
        $this->makePrivate($node);
        return $node;
    }
    private function shouldSkip(\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        $classLike = $classMethod->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\Class_) {
            return \true;
        }
        if ($this->shouldSkipClassLike($classLike)) {
            return \true;
        }
        if ($this->hasTagByName($classMethod, self::API)) {
            return \true;
        }
        if ($this->isControllerAction($classLike, $classMethod)) {
            return \true;
        }
        if ($this->shouldSkipClassMethod($classMethod)) {
            return \true;
        }
        // is interface required method? skip it
        if ($this->classMethodVisibilityVendorLockResolver->isParentLockedMethod($classMethod)) {
            return \true;
        }
        if ($this->classMethodVisibilityVendorLockResolver->isChildLockedMethod($classMethod)) {
            return \true;
        }
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return \false;
        }
        return $phpDocInfo->hasByNames([self::API, \Rector\PhpAttribute\ValueObject\TagName::REQUIRED]);
    }
    private function isControllerAction(\PhpParser\Node\Stmt\Class_ $class, \PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        $className = $class->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        if ($className === null) {
            return \false;
        }
        if (!\RectorPrefix20210118\Nette\Utils\Strings::match($className, self::CONTROLLER_PRESENTER_SUFFIX_REGEX)) {
            return \false;
        }
        $classMethodName = $this->getName($classMethod);
        if ((bool) \RectorPrefix20210118\Nette\Utils\Strings::match($classMethodName, self::COMMON_PUBLIC_METHOD_CONTROLLER_REGEX)) {
            return \true;
        }
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return \false;
        }
        return $phpDocInfo->hasByName('inject');
    }
    private function shouldSkipClassMethod(\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        if ($this->hasTagByName($classMethod, self::API)) {
            return \true;
        }
        if ($classMethod->isPrivate()) {
            return \true;
        }
        if ($classMethod->isAbstract()) {
            return \true;
        }
        // skip for now
        if ($classMethod->isStatic()) {
            return \true;
        }
        if ($this->isName($classMethod, '__*')) {
            return \true;
        }
        // possibly container service factories
        return $this->isNames($classMethod, ['create', 'create*']);
    }
    private function shouldSkipClassLike(\PhpParser\Node\Stmt\Class_ $class) : bool
    {
        if ($this->isAnonymousClass($class)) {
            return \true;
        }
        if ($this->isDoctrineEntityClass($class)) {
            return \true;
        }
        if ($this->isObjectType($class, 'PHPUnit\\Framework\\TestCase')) {
            return \true;
        }
        return $this->hasTagByName($class, self::API);
    }
}
