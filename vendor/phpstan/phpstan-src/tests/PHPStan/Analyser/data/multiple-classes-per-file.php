<?php

namespace _PhpScopera143bcca66cb\MultipleClasses;

class Foo
{
    /**
     * @param self $self
     */
    public function doFoo($self)
    {
        'Foo';
    }
    /**
     * @return self
     */
    public function returnSelf()
    {
    }
}
class Bar
{
    /**
     * @param self $self
     */
    public function doFoo($self)
    {
        'Bar';
    }
    /**
     * @return self
     */
    public function returnSelf()
    {
    }
}
