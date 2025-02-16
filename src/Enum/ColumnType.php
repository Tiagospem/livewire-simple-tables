<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Enum;

enum ColumnType: string
{
    case TEXT    = 'text';
    case ACTION  = 'action';
    case BOOLEAN = 'boolean';

    public function isText(): bool
    {
        return self::TEXT === $this;
    }

    public function isAction(): bool
    {
        return self::ACTION === $this;
    }

    public function isBoolean(): bool
    {
        return self::BOOLEAN === $this;
    }
}
