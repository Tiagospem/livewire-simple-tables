<?php

namespace TiagoSpem\SimpleTables\Traits;

use BackedEnum;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

trait HandlePermission
{
    /**
     * @param  string|BackedEnum|array<string|BackedEnum>  $permission
     */
    protected function resolvePermissionCheck(mixed $permission): bool
    {
        if (is_array($permission)) {
            return collect($permission)->every(
                fn ($p) => $this->checkSinglePermission($p)
            );
        }

        return $this->checkSinglePermission($permission);
    }

    protected function checkSinglePermission(mixed $permission): bool
    {
        $user = Auth::user();

        if (! $user instanceof Authorizable) {
            throw new InvalidArgumentException(
                'User must implement Authorizable interface');
        }

        $permissionName = match (true) {
            $permission instanceof BackedEnum => (string) $permission->value,
            is_string($permission) => $permission,
            is_int($permission) => (string) $permission,
            default => throw new InvalidArgumentException(
                'Invalid permission type: '.gettype($permission))
        };

        return $user->can($permissionName);
    }
}
