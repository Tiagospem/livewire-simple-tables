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

    public function url(Closure $href, bool $_target = false): self
    {
        $this->actionButton['href'] = $href;
        $this->actionButton['target'] = $_target;

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
        return $this->actionButton['href'] ?? null;
    }

    public function getUrlTargetBlank(): string
    {
        return $this->actionButton['target'];
    }

    private function createViewCallback(string $view, string $rowName, array $customParams): Closure
    {
        return fn (mixed $row) => view(view: $view, data: [$rowName => $row, ...$customParams]);
    }
}
