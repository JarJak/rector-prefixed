<?php

declare (strict_types=1);
namespace _PhpScoper0a2ac50786fa\PHPStan\Rules\Generics;

use _PhpScoper0a2ac50786fa\PhpParser\Node\Name;
use _PhpScoper0a2ac50786fa\PHPStan\Reflection\ReflectionProvider;
use _PhpScoper0a2ac50786fa\PHPStan\Rules\MissingTypehintCheck;
use _PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrorBuilder;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Generic\GenericObjectType;
use _PhpScoper0a2ac50786fa\PHPStan\Type\Generic\TemplateTypeVariance;
use _PhpScoper0a2ac50786fa\PHPStan\Type\VerbosityLevel;
class GenericAncestorsCheck
{
    /** @var \PHPStan\Reflection\ReflectionProvider */
    private $reflectionProvider;
    /** @var \PHPStan\Rules\Generics\GenericObjectTypeCheck */
    private $genericObjectTypeCheck;
    /** @var \PHPStan\Rules\Generics\VarianceCheck */
    private $varianceCheck;
    /** @var bool */
    private $checkGenericClassInNonGenericObjectType;
    public function __construct(\_PhpScoper0a2ac50786fa\PHPStan\Reflection\ReflectionProvider $reflectionProvider, \_PhpScoper0a2ac50786fa\PHPStan\Rules\Generics\GenericObjectTypeCheck $genericObjectTypeCheck, \_PhpScoper0a2ac50786fa\PHPStan\Rules\Generics\VarianceCheck $varianceCheck, bool $checkGenericClassInNonGenericObjectType)
    {
        $this->reflectionProvider = $reflectionProvider;
        $this->genericObjectTypeCheck = $genericObjectTypeCheck;
        $this->varianceCheck = $varianceCheck;
        $this->checkGenericClassInNonGenericObjectType = $checkGenericClassInNonGenericObjectType;
    }
    /**
     * @param array<\PhpParser\Node\Name> $nameNodes
     * @param array<\PHPStan\Type\Type> $ancestorTypes
     * @return \PHPStan\Rules\RuleError[]
     */
    public function check(array $nameNodes, array $ancestorTypes, string $incompatibleTypeMessage, string $noNamesMessage, string $noRelatedNameMessage, string $classNotGenericMessage, string $notEnoughTypesMessage, string $extraTypesMessage, string $typeIsNotSubtypeMessage, string $invalidTypeMessage, string $genericClassInNonGenericObjectType, string $invalidVarianceMessage) : array
    {
        $names = \array_fill_keys(\array_map(static function (\_PhpScoper0a2ac50786fa\PhpParser\Node\Name $nameNode) : string {
            return $nameNode->toString();
        }, $nameNodes), \true);
        $unusedNames = $names;
        $messages = [];
        foreach ($ancestorTypes as $ancestorType) {
            if (!$ancestorType instanceof \_PhpScoper0a2ac50786fa\PHPStan\Type\Generic\GenericObjectType) {
                $messages[] = \_PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrorBuilder::message(\sprintf($incompatibleTypeMessage, $ancestorType->describe(\_PhpScoper0a2ac50786fa\PHPStan\Type\VerbosityLevel::typeOnly())))->build();
                continue;
            }
            $ancestorTypeClassName = $ancestorType->getClassName();
            if (!isset($names[$ancestorTypeClassName])) {
                if (\count($names) === 0) {
                    $messages[] = \_PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrorBuilder::message($noNamesMessage)->build();
                } else {
                    $messages[] = \_PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrorBuilder::message(\sprintf($noRelatedNameMessage, $ancestorTypeClassName, \implode(', ', \array_keys($names))))->build();
                }
                continue;
            }
            unset($unusedNames[$ancestorTypeClassName]);
            $genericObjectTypeCheckMessages = $this->genericObjectTypeCheck->check($ancestorType, $classNotGenericMessage, $notEnoughTypesMessage, $extraTypesMessage, $typeIsNotSubtypeMessage);
            $messages = \array_merge($messages, $genericObjectTypeCheckMessages);
            foreach ($ancestorType->getReferencedClasses() as $referencedClass) {
                if ($this->reflectionProvider->hasClass($referencedClass) && !$this->reflectionProvider->getClass($referencedClass)->isTrait()) {
                    continue;
                }
                $messages[] = \_PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrorBuilder::message(\sprintf($invalidTypeMessage, $referencedClass))->build();
            }
            $variance = \_PhpScoper0a2ac50786fa\PHPStan\Type\Generic\TemplateTypeVariance::createInvariant();
            $messageContext = \sprintf($invalidVarianceMessage, $ancestorType->describe(\_PhpScoper0a2ac50786fa\PHPStan\Type\VerbosityLevel::typeOnly()));
            foreach ($this->varianceCheck->check($variance, $ancestorType, $messageContext) as $message) {
                $messages[] = $message;
            }
        }
        if ($this->checkGenericClassInNonGenericObjectType) {
            foreach (\array_keys($unusedNames) as $unusedName) {
                if (!$this->reflectionProvider->hasClass($unusedName)) {
                    continue;
                }
                $unusedNameClassReflection = $this->reflectionProvider->getClass($unusedName);
                if (!$unusedNameClassReflection->isGeneric()) {
                    continue;
                }
                $messages[] = \_PhpScoper0a2ac50786fa\PHPStan\Rules\RuleErrorBuilder::message(\sprintf($genericClassInNonGenericObjectType, $unusedName, \implode(', ', \array_keys($unusedNameClassReflection->getTemplateTypeMap()->getTypes()))))->tip(\_PhpScoper0a2ac50786fa\PHPStan\Rules\MissingTypehintCheck::TURN_OFF_NON_GENERIC_CHECK_TIP)->build();
            }
        }
        return $messages;
    }
}
