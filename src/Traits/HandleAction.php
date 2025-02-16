<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Closure;
use TiagoSpem\SimpleTables\Enum\Target;

trait HandleAction
{
    /** @var array{icon: string|null, name: string|null} */
    protected array $button = [
        'icon' => null,
        'name' => null,
    ];

    /** @var Closure(mixed): bool|bool */
    protected Closure|bool $disabled = false;

    /** @var Closure(mixed): bool|bool */
    protected Closure|bool $hidden = false;

    protected ?string $buttonStyle = null;

    protected ?string $iconStyle = null;

    /** @var array{href: Closure|string, target: Target::*} */
    protected array $hrefData = [
        'href' => '',
        'target' => Target::PARENT,
        'wireNavigate' => false,
    ];

    /** @var array{name: string, params: mixed} */
    protected array $eventData = [
        'name' => '',
        'params' => null,
    ];

    /**
     * @param  Closure(mixed): string|string  $href
     */
    public function href(Closure|string $href, bool $wireNavigate = false, ?Target $target = null): self
    {
        $this->hrefData = [
            'href' => $href,
            'target' => $target ?? Target::PARENT,
            'wireNavigate' => $wireNavigate,
        ];

        return $this;
    }

    public function event(string $name, Closure|array|int|bool|string|null $params = null): self
    {
        $this->eventData = [
            'name' => $name,
            'params' => $params,
        ];

        return $this;
    }

    /**
     * @param  Closure(mixed): bool|bool  $disabled
     */
    public function disabled(Closure|bool $disabled = true): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * @param  Closure(mixed): bool|bool  $hidden
     */
    public function hidden(Closure|bool $hidden = true): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @param  Closure(mixed): bool|bool  $can
     */
    public function can(Closure|bool $can = true): self
    {
        $this->hidden = ! $can;

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

    public function isWireNavigate(): bool
    {
        return $this->hrefData['wireNavigate'];
    }

    public function isDisabled(mixed $row): bool
    {
        return $this->evaluateCondition($this->disabled, $row);
    }

    public function isHidden(mixed $row): bool
    {
        return $this->evaluateCondition($this->hidden, $row);
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
        $result = $this->evaluateValue($this->hrefData['href'], $row);

        return is_string($result) ? $result : null;
    }

    public function getTarget(): string
    {
        return $this->hrefData['target']->value;
    }

    /**
     * @return array{name: string, params: mixed}|null
     */
    public function getEvent(mixed $row): ?array
    {
        if (empty($this->eventData['name'])) {
            return null;
        }

        return [
            'name' => $this->eventData['name'],
            'params' => $this->evaluateValue($this->eventData['params'], $row),
        ];
    }

    public function getName(): ?string
    {
        return $this->button['name'];
    }

    public function getIcon(): ?string
    {
        return $this->button['icon'];
    }

    public function getStyle(): ?string
    {
        return $this->buttonStyle;
    }

    public function getIconStyle(): ?string
    {
        return $this->iconStyle;
    }

    /**
     * @param  Closure(mixed): mixed|mixed  $value
     */
    private function evaluateValue(mixed $value, mixed $row): mixed
    {
        return $value instanceof Closure ? $value($row) : $value;
    }

    /**
     * @param  Closure(mixed): bool|bool  $condition
     */
    private function evaluateCondition(mixed $condition, mixed $row): bool
    {
        return (bool) ($condition instanceof Closure
            ? $condition($row)
            : $condition);
    }
}
