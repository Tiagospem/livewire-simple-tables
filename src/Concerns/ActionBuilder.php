<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use Closure;
use Illuminate\View\View;
use TiagoSpem\SimpleTables\Enum\Target;
use TiagoSpem\SimpleTables\Interfaces\HasActions;
use TiagoSpem\SimpleTables\Option;
use TiagoSpem\SimpleTables\Traits\HandleAction;

final class ActionBuilder implements HasActions
{
    use HandleAction;

    private ?string $name = null;

    private ?Closure $view = null;

    private ?string $defaultOptionIcon = null;

    /**
     * @var array<Option>
     */
    private array $dropdown = [];

    /**
     * @param  array<Option>  $options
     */
    public function dropdown(array $options): self
    {
        $this->dropdown = $options;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function view(string $view, string $rowName = 'row', array $params = []): self
    {
        $this->view = fn(mixed $row) => view($view, [$rowName => $row, ...$params]);

        return $this;
    }

    public function button(?string $icon = null, ?string $name = null, string|Closure|null $href = null, Target $target = Target::PARENT): self
    {
        if (filled($icon) || filled($name)) {
            $this->button = [
                'icon' => $icon,
                'name' => $name,
            ];

            if (filled($href)) {
                $this->hrefData = [
                    'href'   => $href,
                    'target' => $target,
                ];
            }

            if (filled($icon)) {
                $this->iconStyle = 'size-4';
            }
        }

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function defaultOptionIcon(string $icon): self
    {
        $this->defaultOptionIcon = $icon;

        return $this;
    }

    public function hasAction(): bool
    {
        if ($this->hasView()) {
            return true;
        }
        if ($this->hasName()) {
            return true;
        }

        return $this->hasIcon();
    }

    public function hasView(): bool
    {
        return $this->view instanceof Closure;
    }

    public function hasDropdown(): bool
    {
        return [] !== $this->dropdown;
    }

    /**
     * @return array<Option>
     */
    public function getActionOptions(): array
    {
        return $this->dropdown;
    }

    public function getView(mixed $row): ?View
    {
        return $this->view instanceof Closure ? ($this->view)($row) : null;
    }

    public function getDefaultOptionIcon(): ?string
    {
        return $this->defaultOptionIcon;
    }

    public function getActionColumnName(): ?string
    {
        return $this->name;
    }
}
