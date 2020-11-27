<?php

namespace _PhpScoper26e51eeacccf;

class Error implements \Throwable
{
    /** @implementation-alias Exception::__clone */
    private final function __clone() : void
    {
    }
    /** @implementation-alias Exception::__construct */
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
    }
    /**
     * @return void
     * @implementation-alias Exception::__wakeup
     */
    public function __wakeup()
    {
    }
    /** @implementation-alias Exception::getMessage */
    public final function getMessage() : string
    {
    }
    /**
     * @return int
     * @implementation-alias Exception::getCode
     */
    public final function getCode()
    {
    }
    /** @implementation-alias Exception::getFile */
    public final function getFile() : string
    {
    }
    /** @implementation-alias Exception::getLine */
    public final function getLine() : int
    {
    }
    /** @implementation-alias Exception::getTrace */
    public final function getTrace() : array
    {
    }
    /** @implementation-alias Exception::getPrevious */
    public final function getPrevious() : ?\Throwable
    {
    }
    /** @implementation-alias Exception::getTraceAsString */
    public final function getTraceAsString() : string
    {
    }
    /** @implementation-alias Exception::__toString */
    public function __toString() : string
    {
    }
}
\class_alias('_PhpScoper26e51eeacccf\\Error', 'Error', \false);
