<?php

declare (strict_types=1);
namespace Rector\Renaming\Tests\Rector\Name\RenameClassRector;

use Iterator;
use RectorPrefix20201226\Manual\Twig\TwigFilter;
use RectorPrefix20201226\Manual_Twig_Filter;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Fixture\DuplicatedClass;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\AbstractManualExtension;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\NewClass;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\NewClassWithoutTypo;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\OldClass;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\OldClassWithTypo;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class RenameClassRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    /**
     * @see https://github.com/rectorphp/rector/issues/1438
     */
    public function testClassNameDuplication() : void
    {
        $fixtureFileInfo = new \Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/FixtureDuplication/skip_duplicated_class.php.inc');
        $this->doTestFileInfo($fixtureFileInfo);
    }
    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration() : array
    {
        return [\Rector\Renaming\Rector\Name\RenameClassRector::class => [\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => [
            'FqnizeNamespaced' => 'RectorPrefix20201226\\Abc\\FqnizeNamespaced',
            \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\OldClass::class => \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\NewClass::class,
            \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\OldClassWithTypo::class => \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\NewClassWithoutTypo::class,
            'DateTime' => 'DateTimeInterface',
            'Countable' => 'stdClass',
            \RectorPrefix20201226\Manual_Twig_Filter::class => \RectorPrefix20201226\Manual\Twig\TwigFilter::class,
            'Twig_AbstractManualExtension' => \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\AbstractManualExtension::class,
            'Twig_Extension_Sandbox' => 'RectorPrefix20201226\\Twig\\Extension\\SandboxExtension',
            // Renaming class itself and its namespace
            'RectorPrefix20201226\\MyNamespace\\MyClass' => 'RectorPrefix20201226\\MyNewNamespace\\MyNewClass',
            'RectorPrefix20201226\\MyNamespace\\MyTrait' => 'RectorPrefix20201226\\MyNewNamespace\\MyNewTrait',
            'RectorPrefix20201226\\MyNamespace\\MyInterface' => 'RectorPrefix20201226\\MyNewNamespace\\MyNewInterface',
            'MyOldClass' => 'RectorPrefix20201226\\MyNamespace\\MyNewClass',
            'AnotherMyOldClass' => 'AnotherMyNewClass',
            'RectorPrefix20201226\\MyNamespace\\AnotherMyClass' => 'MyNewClassWithoutNamespace',
            // test duplicated class - @see https://github.com/rectorphp/rector/issues/1438
            'Rector\\Renaming\\Tests\\Rector\\Name\\RenameClassRector\\Fixture\\SingularClass' => \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Fixture\DuplicatedClass::class,
        ]]];
    }
}
