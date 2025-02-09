<?php

namespace TiagoSpem\SimpleTables;

final class Option
{
    // public string $style = '';

    // public bool $disabled = false;

    // public string $href = '';

    // public string $target = '_parent';

    // public string $eventName = '';

    // public array $eventParams = [];

    public static function add(): Option
    {
        return new self;
    }

    public static function divider(): Option
    {
        return new self;
    }
}
