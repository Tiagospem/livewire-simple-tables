<?php

namespace TiagoSpem\SimpleTables;

use Closure;
use Illuminate\View\View;

class SimpleTablesActionBuilder
{
    private ?Closure $view = null;

    /**
     * @var array{
     *     icon?: string|null,
     *     name?: string|null,
     *     href?: Closure|null,
     *     target?: string,
     *     disabled?: bool|Closure,
     *     hidden?: bool|Closure
     * }
     */
    private array $actionButton = [];

    /**
     * @var array{
     *     name?: string,
     *     params?: Closure
     * }
     */
    private array $actionEvent = [];

    private string $actionIconStyle = 'size-4';

    private ?string $defaultDropdownOptionIcon = null;

    /**
     * @var array<Option>
     */
    private array $dropdown = [];

    public function actionIconStyle(string $style): self
    {
        $this->actionIconStyle = $style;

        return $this;
    }

    public function button(?string $icon = null, ?string $name = null, ?Closure $href = null, string $target = '_parent'): self
    {
        if (filled($icon) || filled($name)) {
            $this->actionButton = [
                'icon' => $icon,
                'name' => $name,
                'href' => $href,
                'target' => $target,
            ];
        }

        return $this;
    }

    public function href(Closure $href, string $target = '_parent'): self
    {
        $this->actionButton['href'] = $href;
        $this->actionButton['target'] = $target;

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

    /**
     * @param  array<Option>  $options
     */
    public function dropdown(array $options): self
    {
        $this->dropdown = $options;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $customParams
     */
    public function view(string $view, string $rowName = 'row', array $customParams = []): self
    {
        $this->view = fn (mixed $row) => view($view, [$rowName => $row, ...$customParams]);

        return $this;
    }

    public function disabled(Closure|bool $disabled = true): self
    {
        $this->actionButton['disabled'] = $disabled;

        return $this;
    }

    public function hidden(Closure|bool $hidden = true): self
    {
        $this->actionButton['hidden'] = $hidden;

        return $this;
    }

    public function defaultDropdownOptionIcon(string $icon): self
    {
        $this->defaultDropdownOptionIcon = $icon;

        return $this;
    }

    public function hasActions(): bool
    {
        if ($this->hasActionView()) {
            return true;
        }

        return $this->hasActionButton();
    }

    public function hasActionButton(): bool
    {
        return $this->actionButton !== [];
    }

    public function hasButtonName(): bool
    {
        return isset($this->actionButton['name']) && filled($this->actionButton['name']);
    }

    public function hasActionView(): bool
    {
        return $this->view instanceof Closure;
    }

    public function hasIcon(): bool
    {
        return ! empty($this->actionButton['icon']);
    }

    public function hasDropdown(): bool
    {
        return $this->dropdown !== [];
    }

    public function getButtonName(): string
    {
        return (string) ($this->actionButton['name'] ?? '');
    }

    public function getButtonIcon(): string
    {
        return (string) ($this->actionButton['icon'] ?? '');
    }

    public function getActionIconStyle(): string
    {
        return $this->actionIconStyle;
    }

    public function getActionUrl(mixed $row): ?string
    {
        $urlCallback = $this->actionButton['href'] ?? null;

        return $urlCallback instanceof Closure ? $urlCallback($row) : null;
    }

    public function getActionUrlTarget(): string
    {
        return (string) ($this->actionButton['target'] ?? '_parent');
    }

    public function getEventName(): string
    {
        return (string) ($this->actionEvent['name'] ?? '');
    }

    public function getEventParams(mixed $row): mixed
    {
        $eventParamsCallback = $this->actionEvent['params'] ?? null;

        return $eventParamsCallback instanceof Closure ? $eventParamsCallback($row) : null;
    }

    public function getActionView(mixed $row): ?View
    {
        return $this->view instanceof Closure ? ($this->view)($row) : null;
    }

    public function getIsActionDisabled(mixed $row): bool
    {
        $disabled = $this->actionButton['disabled'] ?? false;

        if ($disabled instanceof Closure) {
            return (bool) $disabled($row);
        }

        return (bool) $disabled;
    }

    public function getDefaultDropdownOptionIcon(): ?string
    {
        return $this->defaultDropdownOptionIcon;
    }

    public function getIsHidden(mixed $row): bool
    {
        $hidden = $this->actionButton['hidden'] ?? false;

        if ($hidden instanceof Closure) {
            return (bool) $hidden($row);
        }

        return (bool) $hidden;
    }

    /**
     * @return array<Option>
     */
    public function getActionOptions(): array
    {
        return $this->dropdown;
    }
}
