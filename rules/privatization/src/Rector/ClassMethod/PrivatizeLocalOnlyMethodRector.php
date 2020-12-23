<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\Rector\Privatization\Rector\ClassMethod;

use _PhpScoper0a2ac50786fa\Nette\Utils\Strings;
use _PhpScoper0a2ac50786fa\PhpParser\Node;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_;
use _PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoper0a2ac50786fa\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use _PhpScoper0a2ac50786fa\Rector\Caching\Contract\Rector\ZeroCacheRectorInterface;
use _PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector;
use _PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoper0a2ac50786fa\Rector\PhpAttribute\ValueObject\TagName;
use _PhpScoper0a2ac50786fa\Rector\Privatization\NodeAnalyzer\ClassMethodExternalCallNodeAnalyzer;
use _PhpScoper0a2ac50786fa\Rector\VendorLocker\NodeVendorLocker\ClassMethodVisibilityVendorLockResolver;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Privatization\Tests\Rector\ClassMethod\PrivatizeLocalOnlyMethodRector\PrivatizeLocalOnlyMethodRectorTest
 */
final class PrivatizeLocalOnlyMethodRector extends \_PhpScoper0a2ac50786fa\Rector\Core\Rector\AbstractRector implements \_PhpScoper0a2ac50786fa\Rector\Caching\Contract\Rector\ZeroCacheRectorInterface
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
     * @var ClassMethodVisibilityVendorLockResolver
     */
    private $classMethodVisibilityVendorLockResolver;
    /**
     * @var ClassMethodExternalCallNodeAnalyzer
     */
    private $classMethodExternalCallNodeAnalyzer;
    public function __construct(\_PhpScoper0a2ac50786fa\Rector\Privatization\NodeAnalyzer\ClassMethodExternalCallNodeAnalyzer $classMethodExternalCallNodeAnalyzer, \_PhpScoper0a2ac50786fa\Rector\VendorLocker\NodeVendorLocker\ClassMethodVisibilityVendorLockResolver $classMethodVisibilityVendorLockResolver)
    {
        $this->classMethodVisibilityVendorLockResolver = $classMethodVisibilityVendorLockResolver;
        $this->classMethodExternalCallNodeAnalyzer = $classMethodExternalCallNodeAnalyzer;
    }
    public function getRuleDefinition() : \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Privatize local-only use methods', [new \_PhpScoper0a2ac50786fa\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
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
        return [\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod $node
     */
    public function refactor(\_PhpScoper0a2ac50786fa\PhpParser\Node $node) : ?\_PhpScoper0a2ac50786fa\PhpParser\Node
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
    private function shouldSkip(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        $classLike = $classMethod->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_) {
            return \true;
        }
        if ($this->isAnonymousClass($classLike)) {
            return \true;
        }
        if ($this->isObjectType($classLike, '_PhpScoper0a2ac50786fa\\PHPUnit\\Framework\\TestCase')) {
            return \true;
        }
        if ($this->isDoctrineEntityClass($classLike)) {
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
        $phpDocInfo = $classMethod->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return \false;
        }
        return $phpDocInfo->hasByNames(['api', \_PhpScoper0a2ac50786fa\Rector\PhpAttribute\ValueObject\TagName::REQUIRED]);
    }
    private function isControllerAction(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\Class_ $class, \_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
        $className = $class->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        if ($className === null) {
            return \false;
        }
        if (!\_PhpScoper0a2ac50786fa\Nette\Utils\Strings::match($className, self::CONTROLLER_PRESENTER_SUFFIX_REGEX)) {
            return \false;
        }
        $classMethodName = $this->getName($classMethod);
        if ((bool) \_PhpScoper0a2ac50786fa\Nette\Utils\Strings::match($classMethodName, self::COMMON_PUBLIC_METHOD_CONTROLLER_REGEX)) {
            return \true;
        }
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(\_PhpScoper0a2ac50786fa\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return \false;
        }
        return $phpDocInfo->hasByName('inject');
    }
    private function shouldSkipClassMethod(\_PhpScoper0a2ac50786fa\PhpParser\Node\Stmt\ClassMethod $classMethod) : bool
    {
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
}
