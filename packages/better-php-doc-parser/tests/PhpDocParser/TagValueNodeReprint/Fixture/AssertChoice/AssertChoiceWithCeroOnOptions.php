<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\AssertChoice;

use RectorPrefix20210118\Symfony\Component\Validator\Constraints as Assert;
class AssertChoiceWithCeroOnOptions
{
    /**
     * @Assert\Choice(choices={"0", "3023", "3610"})
     */
    public $ratingType;
}
