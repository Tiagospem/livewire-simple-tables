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

    public function add(string $name, string $eventName, ?string $icon = null): self
    {
        $this->name      = $name;
        $this->icon      = $icon;
        $this->eventName = $eventName;

        return $this;
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

    public function event(string $name): self
    {
        $this->eventName = $name;

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

    public function hasIcon(): bool
    {
        return filled($this->icon);
    }
}
