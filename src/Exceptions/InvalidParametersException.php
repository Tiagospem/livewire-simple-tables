<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Exceptions;

use Exception;

final class InvalidParametersException extends Exception
{
    public function __construct(string $message = 'Invalid parameters')
    {
        parent::__construct($message);
    }
}
