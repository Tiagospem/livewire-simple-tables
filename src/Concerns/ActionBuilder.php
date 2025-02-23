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

    /**
     * @param  array<Action>  $action
     */
    public function actions(array $action): self
    {
        $this->actions = $action;

        return $this;
    }

    /**
     * @return array<Action>
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function hasActions(): bool
    {
        return [] !== $this->actions;
    }
}
