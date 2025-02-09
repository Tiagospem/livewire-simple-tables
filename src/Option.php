<?php

namespace TiagoSpem\SimpleTables;

final class Option
{
    private ?string $name = null;

    private ?string $icon = null;

    private bool $isDivider = false;

    // private bool $disabled = false;

    // public string $style = '';

    // public bool $disabled = false;

    // public string $href = '';

    // public string $target = '_parent';

    // public string $eventName = '';

    // public array $eventParams = [];
    public static function make(): Option
    {
        return new self;
    }

    public static function add(string $name, ?string $icon = null): self
    {
        $option = new self;

        $option->name = $name;
        $option->icon = $icon;

        return $option;
    }

    public static function divider(): self
    {
        $option = new self;
        $option->isDivider = true;

        return $option;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getIsDivider(): bool
    {
        return $this->isDivider;
    }
}
