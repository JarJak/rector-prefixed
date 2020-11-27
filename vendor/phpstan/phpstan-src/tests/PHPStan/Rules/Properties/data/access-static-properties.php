<?php

namespace _PhpScoper006a73f0e455;

// lint < 8.0
class FooAccessStaticProperties
{
    public static $test;
    protected static $foo;
    public $loremIpsum;
}
// lint < 8.0
\class_alias('_PhpScoper006a73f0e455\\FooAccessStaticProperties', 'FooAccessStaticProperties', \false);
class BarAccessStaticProperties extends \_PhpScoper006a73f0e455\FooAccessStaticProperties
{
    public static function test()
    {
        \_PhpScoper006a73f0e455\FooAccessStaticProperties::$test;
        \_PhpScoper006a73f0e455\FooAccessStaticProperties::$foo;
        parent::$test;
        parent::$foo;
        \_PhpScoper006a73f0e455\FooAccessStaticProperties::$bar;
        // nonexistent
        self::$bar;
        // nonexistent
        parent::$bar;
        // nonexistent
        \_PhpScoper006a73f0e455\FooAccessStaticProperties::$loremIpsum;
        // instance
        static::$foo;
    }
    public function loremIpsum()
    {
        parent::$loremIpsum;
    }
}
\class_alias('_PhpScoper006a73f0e455\\BarAccessStaticProperties', 'BarAccessStaticProperties', \false);
class IpsumAccessStaticProperties
{
    public static function ipsum()
    {
        parent::$lorem;
        // does not have a parent
        \_PhpScoper006a73f0e455\FooAccessStaticProperties::$test;
        \_PhpScoper006a73f0e455\FooAccessStaticProperties::$foo;
        // protected and not from a parent
        \_PhpScoper006a73f0e455\FooAccessStaticProperties::${$foo};
        $class::$property;
        \_PhpScoper006a73f0e455\UnknownStaticProperties::$test;
        if (isset(static::$baz)) {
            static::$baz;
        }
        isset(static::$baz);
        static::$baz;
        if (!isset(static::$nonexistent)) {
            static::$nonexistent;
            return;
        }
        static::$nonexistent;
        if (!empty(static::$emptyBaz)) {
            static::$emptyBaz;
        }
        static::$emptyBaz;
        if (empty(static::$emptyNonexistent)) {
            static::$emptyNonexistent;
            return;
        }
        static::$emptyNonexistent;
        isset(static::$anotherNonexistent) ? static::$anotherNonexistent : null;
        isset(static::$anotherNonexistent) ? null : static::$anotherNonexistent;
        !isset(static::$anotherNonexistent) ? static::$anotherNonexistent : null;
        !isset(static::$anotherNonexistent) ? null : static::$anotherNonexistent;
        empty(static::$anotherEmptyNonexistent) ? static::$anotherEmptyNonexistent : null;
        empty(static::$anotherEmptyNonexistent) ? null : static::$anotherEmptyNonexistent;
        !empty(static::$anotherEmptyNonexistent) ? static::$anotherEmptyNonexistent : null;
        !empty(static::$anotherEmptyNonexistent) ? null : static::$anotherEmptyNonexistent;
    }
}
\class_alias('_PhpScoper006a73f0e455\\IpsumAccessStaticProperties', 'IpsumAccessStaticProperties', \false);
function () {
    self::$staticFooProperty;
    static::$staticFooProperty;
    parent::$staticFooProperty;
    \_PhpScoper006a73f0e455\FooAccessStaticProperties::$test;
    \_PhpScoper006a73f0e455\FooAccessStaticProperties::$foo;
    \_PhpScoper006a73f0e455\FooAccessStaticProperties::$loremIpsum;
    $foo = new \_PhpScoper006a73f0e455\FooAccessStaticProperties();
    $foo::$test;
    $foo::$nonexistent;
    $bar = new \_PhpScoper006a73f0e455\NonexistentClass();
    $bar::$test;
};
interface SomeInterface
{
}
\class_alias('_PhpScoper006a73f0e455\\SomeInterface', 'SomeInterface', \false);
function (\_PhpScoper006a73f0e455\FooAccessStaticProperties $foo) {
    if ($foo instanceof \_PhpScoper006a73f0e455\SomeInterface) {
        $foo::$test;
        $foo::$nonexistent;
    }
    /** @var string|int $stringOrInt */
    $stringOrInt = \_PhpScoper006a73f0e455\doFoo();
    $stringOrInt::$foo;
};
function (\_PhpScoper006a73f0e455\FOOAccessStaticPropertieS $foo) {
    $foo::$test;
    // do not report case mismatch
    \_PhpScoper006a73f0e455\FOOAccessStaticPropertieS::$unknownProperties;
    \_PhpScoper006a73f0e455\FOOAccessStaticPropertieS::$loremIpsum;
    \_PhpScoper006a73f0e455\FOOAccessStaticPropertieS::$foo;
    \_PhpScoper006a73f0e455\FOOAccessStaticPropertieS::$test;
};
function (string $className) {
    $className::$fooProperty;
};
class ClassOrString
{
    private static $accessedProperty;
    private $instanceProperty;
    public function doFoo()
    {
        /** @var self|string $class */
        $class = \_PhpScoper006a73f0e455\doFoo();
        $class::$accessedProperty;
        $class::$unknownProperty;
        \_PhpScoper006a73f0e455\Self::$accessedProperty;
    }
    public function doBar()
    {
        /** @var self|false $class */
        $class = \_PhpScoper006a73f0e455\doFoo();
        if (isset($class::$anotherProperty)) {
            echo $class::$anotherProperty;
            echo $class::$instanceProperty;
        }
    }
}
\class_alias('_PhpScoper006a73f0e455\\ClassOrString', 'ClassOrString', \false);
class AccessPropertyWithDimFetch
{
    public function doFoo()
    {
        self::$foo['foo'] = 'test';
    }
    public function doBar()
    {
        self::$foo = 'test';
        // reported by a separate rule
    }
}
\class_alias('_PhpScoper006a73f0e455\\AccessPropertyWithDimFetch', 'AccessPropertyWithDimFetch', \false);
class AccessInIsset
{
    public function doFoo()
    {
        if (isset(self::$foo)) {
        }
    }
    public function doBar()
    {
        if (isset(self::$foo['foo'])) {
        }
    }
}
\class_alias('_PhpScoper006a73f0e455\\AccessInIsset', 'AccessInIsset', \false);
trait TraitWithStaticProperty
{
    public static $foo;
}
class MethodAccessingTraitProperty
{
    public function doFoo() : void
    {
        echo \_PhpScoper006a73f0e455\TraitWithStaticProperty::$foo;
    }
    public function doBar(\_PhpScoper006a73f0e455\TraitWithStaticProperty $a) : void
    {
        echo $a::$foo;
    }
}
\class_alias('_PhpScoper006a73f0e455\\MethodAccessingTraitProperty', 'MethodAccessingTraitProperty', \false);