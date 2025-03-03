<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use BackedEnum;
use Closure;
use TiagoSpem\SimpleTables\Traits\HandlePermission;

final class Bulk
{
    use HandlePermission;

    private ?string $icon = null;

    private string $name;

    private mixed $can = true;

    private string $eventName;

    public static function event(string $name, string $eventName, ?string $icon = null): self
    {
        $bulk = new self();

        $bulk->name      = $name;
        $bulk->icon      = $icon;
        $bulk->eventName = $eventName;

        return $bulk;
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param  bool|Closure|string|BackedEnum|array<string|BackedEnum>  $permission
     */
    public function can(mixed $permission = true): self
    {
        if (is_bool($permission)) {
            $this->can = $permission;
        } elseif ($permission instanceof Closure) {
            $this->can = $permission;
        } else {
            $this->can = $this->resolvePermissionCheck($permission);
        }

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function hasIcon(): bool
    {
        return filled($this->icon);
    }

    public function evaluatePermission(): bool
    {
        return (bool) ($this->can instanceof Closure
            ? $this->can->call($this)
            : $this->can);
    }
}
