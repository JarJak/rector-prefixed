<?php

namespace _PhpScoperabd03f0baf05\TryCatchScope;

function () {
    $resource = null;
    try {
        $resource = new \_PhpScoperabd03f0baf05\TryCatchScope\Foo();
    } catch (\_PhpScoperabd03f0baf05\TryCatchScope\FooException $e) {
        $resource = new \_PhpScoperabd03f0baf05\TryCatchScope\Foo();
    } catch (\_PhpScoperabd03f0baf05\TryCatchScope\BarException $e) {
        $resource = new \_PhpScoperabd03f0baf05\TryCatchScope\Foo();
    }
    'first';
};
function () {
    $resource = null;
    try {
        $resource = new \_PhpScoperabd03f0baf05\TryCatchScope\Foo();
    } catch (\_PhpScoperabd03f0baf05\TryCatchScope\FooException $e) {
    } catch (\_PhpScoperabd03f0baf05\TryCatchScope\BarException $e) {
        $resource = new \_PhpScoperabd03f0baf05\TryCatchScope\Foo();
    }
    'second';
};
function () {
    $resource = null;
    try {
        $resource = new \_PhpScoperabd03f0baf05\TryCatchScope\Foo();
    } catch (\_PhpScoperabd03f0baf05\TryCatchScope\FooException $e) {
    } catch (\_PhpScoperabd03f0baf05\TryCatchScope\BarException $e) {
    }
    'third';
};
