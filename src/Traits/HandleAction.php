<?php

namespace TiagoSpem\SimpleTables\Traits;

use Closure;
use TiagoSpem\SimpleTables\Enum\Target;

trait HandleAction
{
    /**
     * @var array{
     *     icon?: string|null,
     *     name?: string|null,
     *    }
     * }
     */
    protected array $button = [
        'icon' => null,
        'name' => null,
    ];

    protected Closure|bool $disabled = false;

    protected Closure|bool $hidden = false;

    protected ?string $buttonStyle = null;

    protected ?string $iconStyle = null;

    /**
     * @var array{
     *     href: Closure|string,
     *     target: Target::*,
     *    }
     * }
     */
    protected array $hrefData = [
        'href' => '',
        'target' => Target::PARENT,
    ];

    /**
     * @var array{
     *     name: string,
     *     params: mixed,
     * }
     */
    protected array $eventData = [
        'name' => '',
        'params' => null,
    ];

    public function href(Closure|string $href, ?Target $target = null): self
    {
        $this->hrefData['href'] = $href;
        $this->hrefData['target'] = $target ?? Target::PARENT;

        return $this;
    }

    public function event(string $name, mixed $params = null): self
    {
        $this->eventData = [
            'name' => $name,
            'params' => $params,
        ];

        return $this;
    }

    public function disabled(Closure|bool $disabled = true): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function hidden(Closure|bool $hidden = true): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    public function iconStyle(string $style): self
    {
        $this->iconStyle = $style;

        return $this;
    }

    public function buttonStyle(string $style): self
    {
        $this->buttonStyle = $style;

        return $this;
    }

    public function isDisabled(mixed $row): bool
    {
        $disabled = $this->disabled;

        if ($disabled instanceof Closure) {
            return (bool) $disabled($row);
        }

        return (bool) $disabled;
    }

    public function isHidden(mixed $row): bool
    {
        $hidden = $this->hidden;

        if ($hidden instanceof Closure) {
            return (bool) $hidden($row);
        }

        return (bool) $hidden;
    }

    public function hasName(): bool
    {
        return ! empty($this->button['name']);
    }

    public function hasIcon(): bool
    {
        return ! empty($this->button['icon']);
    }

    public function getUrl(mixed $row): ?string
    {
        $href = $this->hrefData['href'];

        if ($href instanceof Closure) {
            return (string) $href($row);
        }

        return $href ? parserString($href) : null;
    }

    public function getTarget(): string
    {
        return $this->hrefData['target']->value ?? Target::PARENT->value;
    }

    /**
     * @return null|array{
     *     name: string,
     *     params: mixed,
     * }
     */
    public function getEvent(mixed $row): ?array
    {
        if (empty($this->eventData['name'])) {
            return null;
        }

        return [
            'name' => $this->eventData['name'],
            'params' => $this->eventData['params'] instanceof Closure
                ? $this->eventData['params']($row)
                : $this->eventData['params'],
        ];
    }

    public function getName(): ?string
    {
        return $this->button['name'] ?? null;
    }

    public function getIcon(): ?string
    {
        return $this->button['icon'] ?? null;
    }

    public function getStyle(): ?string
    {
        return $this->buttonStyle;
    }

    public function getIconStyle(): ?string
    {
        return $this->iconStyle;
    }
}
