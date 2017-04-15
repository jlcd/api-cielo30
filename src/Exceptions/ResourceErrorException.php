<?php

namespace jlcd\Cielo\Exceptions;

class ResourceErrorException extends \Exception
{
    /**
     * Exception thrown when something goes wrong with the resource call
     * @param int $statusCode
     * @param string $msg
     */
    public function __construct($statusCode, $msg)
    {
        parent::__construct($statusCode, $msg);
    }
}
