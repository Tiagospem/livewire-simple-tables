<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Enum;

enum ColumnType: string
{
    case TEXT    = 'text';
    case ACTION  = 'action';
    case BOOLEAN = 'boolean';
    case TOGGLE  = 'toggle';

    public function isAction(): bool
    {
        return self::ACTION === $this;
    }

    public function isBoolean(): bool
    {
        return self::BOOLEAN === $this;
    }

    public function isToggle(): bool
    {
        return self::TOGGLE === $this;
    }
}
