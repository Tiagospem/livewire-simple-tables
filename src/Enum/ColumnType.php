<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Enum;

enum ColumnType: string
{
    case TEXT = 'text';
    case ACTION = 'action';
    case BOOLEAN = 'boolean';
    case TOGGLE = 'toggle';

    public function isText(): bool
    {
        return $this === self::TEXT;
    }

    public function isAction(): bool
    {
        return $this === self::ACTION;
    }

    public function isBoolean(): bool
    {
        return $this === self::BOOLEAN;
    }

    public function isToggle(): bool
    {
        return $this === self::TOGGLE;
    }
}
