<?php

namespace Rector\DoctrineGedmoToKnplabs\Tests\Rector\Class_\TranslationBehaviorRector\Fixture;

/**
 * @ORM\Entity
 */
class SomeClassTranslation implements \_PhpScoper006a73f0e455\Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface
{
    use \Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
    /**
     * @ORM\Column(length=128)
     */
    private $title;
    /**
     * @ORM\Column(type="text")
     */
    private $content;
}
