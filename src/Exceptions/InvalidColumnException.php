<?php

namespace TiagoSpem\SimpleTables\Exceptions;

use Exception;

class InvalidColumnException extends Exception
{
    public function __construct(string $message = 'Invalid column')
    {
        parent::__construct($message);
    }
}
