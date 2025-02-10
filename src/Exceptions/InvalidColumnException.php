<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Exceptions;

use Exception;

final class InvalidColumnException extends Exception
{
    public function __construct(string $message = 'Invalid column')
    {
        parent::__construct($message);
    }
}
