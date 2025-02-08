<?php

namespace TiagoSpem\SimpleTables;

use Livewire\Wireable;

final class Option implements Wireable
{
    public ?string $title = null;

    public ?string $icon = null;

    // public string $style = '';

    // public bool $disabled = false;

    // public string $href = '';

    // public string $target = '_parent';

    // public string $eventName = '';

    // public array $eventParams = [];

    public static function add(string $title, ?string $icon = null): Option
    {
        $option = new self;

        $option->title = $title;
        $option->icon = $icon;

        return $option;
    }

    public function toLivewire(): array
    {
        return (array) $this;
    }

    public static function fromLivewire($value)
    {
        return $value;
    }
}
