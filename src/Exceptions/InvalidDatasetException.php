<?php

namespace TiagoSpem\SimpleTables\Exceptions;

use Exception;

class InvalidDatasetException extends Exception
{
    public function __construct(string $message = 'Datasource must be an instance of Builder or Collection')
    {
        parent::__construct($message);
    }
}
