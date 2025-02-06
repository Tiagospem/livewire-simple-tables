<?php

namespace TiagoSpem\SimpleTables\Exceptions;

use Exception;

class InvalidParametersException extends Exception
{
    public function __construct(string $message = 'Invalid parameters')
    {
        parent::__construct($message);
    }
}
