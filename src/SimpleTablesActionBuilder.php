<?php

namespace TiagoSpem\SimpleTables;

use Closure;

class SimpleTablesActionBuilder
{
    public ?Closure $view = null;

    public array $actionButton = [];

    public array $actionEvent = [];

    public string $actionIconStyle = 'size-4';

    public function dropdown(): self
    {
        return $this;
    }

    public function actionIconStyle(string $style): self
    {
        $this->actionIconStyle = $style;

        return $this;
    }

    public function button(?string $icon = null, ?string $name = null, ?Closure $href = null, string $_target = '_parent'): self
    {
        if (filled($icon) || filled($name)) {
            $this->actionButton = [
                'icon' => $icon,
                'name' => $name,
                'href' => $href,
                'target' => $_target,
            ];
        }

        return $this;
    }

    public function href(Closure $href, bool $_target = false): self
    {
        $this->actionButton['href'] = $href;
        $this->actionButton['target'] = $_target;

        return $this;
    }

    public function event(string $name, Closure $params): self
    {
        $this->actionEvent = [
            'name' => $name,
            'params' => $params,
        ];

        return $this;
    }

    public function view(string $view, string $rowName = 'row', array $customParams = []): self
    {
        $this->view = fn (mixed $row) => view(view: $view, data: [$rowName => $row, ...$customParams]);

        return $this;
    }

    public function hasActions(): bool
    {
        return filled($this->view) || filled($this->actionButton);
    }

    public function hasButtonName(): bool
    {
        return filled($this->actionButton['name']);
    }

    public function getButtonName(): string
    {
        return $this->actionButton['name'] ?? '';
    }

    public function getButtonIcon(): string
    {
        return $this->actionButton['icon'] ?? '';
    }

    public function getActionIconStyle(): string
    {
        return $this->actionIconStyle;
    }

    public function getActionUrl(mixed $row): ?string
    {
        $urlCallback = $this->actionButton['href'] ?? null;

        if (is_callable($urlCallback)) {
            return $urlCallback($row);
        }

        return null;
    }

    public function getEventName(): string
    {
        return $this->actionEvent['name'] ?? '';
    }

    public function getEventParams(mixed $row): mixed
    {
        $eventParamsCallback = $this->actionEvent['params'] ?? null;

        if (is_callable($eventParamsCallback)) {
            return $eventParamsCallback($row);
        }

        return null;
    }

    public function getActionUrlTarget(): string
    {
        return $this->actionButton['target'];
    }
}
