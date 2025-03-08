<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use TiagoSpem\SimpleTables\Interfaces\HasActionsInterface;
use TiagoSpem\SimpleTables\Traits\HandleAction;

final class Option implements HasActionsInterface
{
    use HandleAction;

    private bool $isDivider = false;

    /**
     * @var array<Option>
     */
    private array $dividerOptions = [];

    public static function add(string $name, ?string $icon = null): self
    {
        $option = new self();

        $option->button = [
            'icon' => $icon,
            'name' => $name,
        ];

        return $option;
    }

    /**
     * @param  array<Option>  $options
     */
    public static function divider(array $options): self
    {
        $option = new self();

        $option->isDivider      = true;
        $option->dividerOptions = $options;

        return $option;
    }

    public function isDivider(): bool
    {
        return $this->isDivider;
    }

    /**
     * @return array<Option>
     */
    public function getDividerOptions(): array
    {
        return $this->dividerOptions;
    }

    public function hasDividerOptions(): bool
    {
        return filled($this->dividerOptions);
    }
}
