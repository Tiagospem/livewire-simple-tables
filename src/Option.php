<?php

namespace TiagoSpem\SimpleTables;

use Closure;

final class Option
{
    private ?string $name = null;

    private ?string $icon = null;

    private bool $isDivider = false;

    private Closure|bool $isHidden = false;

    /**
     * @var array<Option>
     */
    private array $dividerOptions = [];

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

    public function hidden(Closure|bool $hidden = true): self
    {
        $this->isHidden = $hidden;

        return $this;
    }

    /**
     * @param  array<Option>  $options
     */
    public static function divider(array $options): self
    {
        $option = new self;

        $option->isDivider = true;
        $option->dividerOptions = $options;

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

    public function getIsHidden(mixed $row): bool
    {
        $closure = $this->isHidden;

        if ($closure instanceof Closure) {
            return (bool) $closure($row);
        }

        return (bool) $this->isHidden;
    }

    /**
     * @return array<Option>
     */
    public function getDividerOptions(): array
    {
        return $this->dividerOptions;
    }
}
