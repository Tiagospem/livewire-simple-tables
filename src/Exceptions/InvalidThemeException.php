<?php

namespace TiagoSpem\SimpleTables\Exceptions;

use Exception;

class InvalidThemeException extends Exception
{
    public function __construct(string $message = 'Invalid theme')
    {
        parent::__construct($message);
    }
}
