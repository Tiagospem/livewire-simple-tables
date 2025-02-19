<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\Action;

final class ActionBuilder
{
    /**
     * @var array<Action>
     */
    private array $actions = [];

    private string $columnName = '';

    private string $columnStyle = '';

    /**
     * @param  array<Action>  $action
     */
    public function actions(array $action): self
    {
        $this->actions = $action;

        return $this;
    }

    public function columnName(string $columnName): self
    {
        $this->columnName = $columnName;

        return $this;
    }

    public function columnStyle(string $columnStyle): self
    {
        $this->columnStyle = $columnStyle;

        return $this;
    }

    /**
     * @return array<Action>
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function getColumnStyle(): string
    {
        return mergeStyle($this->columnStyle);
    }

    public function getActionColumnName(): string
    {
        return $this->columnName;
    }

    public function hasActions(): bool
    {
        return $this->actions !== [];
    }
}
