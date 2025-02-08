<?php

namespace TiagoSpem\SimpleTables;

use Closure;

class SimpleTablesActionBuilder
{
    public ?Closure $view = null;

    public array $actionButton = [];

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

    public function button(?string $icon = null, ?string $name = null, ?Closure $urlCallback = null, bool $targetBlank = false): self
    {
        if (filled($icon) || filled($name)) {
            $this->actionButton = [
                'icon' => $icon,
                'name' => $name,
                'url_callback' => $urlCallback,
                'target_blank' => $targetBlank,
            ];
        }

        return $this;
    }

    public function url(Closure $urlCallback, bool $targetBlank = false): self
    {
        $this->actionButton['url_callback'] = $urlCallback;
        $this->actionButton['target_blank'] = $targetBlank;

        return $this;
    }

    public function view(string $view, string $rowName = 'row', array $customParams = []): self
    {
        $this->view = $this->createViewCallback(view: $view, rowName: $rowName, customParams: $customParams);

        return $this;
    }

    public function hasActions(): bool
    {
        return filled($this->view) || filled($this->actionButton);
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

    public function getUrlCallback(): ?Closure
    {
        return $this->actionButton['url_callback'] ?? null;
    }

    public function getIsTargetBlank(): bool
    {
        return $this->actionButton['target_blank'] ?? false;
    }

    private function createViewCallback(string $view, string $rowName, array $customParams): Closure
    {
        return fn (mixed $row) => view(view: $view, data: [$rowName => $row, ...$customParams]);
    }
}
