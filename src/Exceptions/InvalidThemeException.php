<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Exceptions;

use Exception;

final class InvalidThemeException extends Exception
{
    public function __construct(string $message = 'Invalid theme')
    {
        parent::__construct($message);
    }
}
