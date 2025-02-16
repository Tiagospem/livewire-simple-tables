<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Interfaces;

use Closure;
use TiagoSpem\SimpleTables\Enum\Target;

interface HasActions
{
    /**
     * @param  Closure(mixed): string|string  $href
     */
    public function href(Closure|string $href, bool $wireNavigate = false, ?Target $target = null): self;

    /**
     * @param  Closure|array<string, mixed>|int|bool|string|null  $params
     */
    public function event(string $name, Closure|array|int|bool|string|null $params = null): self;

    /**
     * @param  Closure(mixed): bool|bool  $disabled
     */
    public function disabled(Closure|bool $disabled = true): self;

    /**
     * @param  Closure(mixed): bool|bool  $hidden
     */
    public function hidden(Closure|bool $hidden = true): self;

    public function can(Closure|bool $can = true): self;

    public function iconStyle(string $style): self;

    public function buttonStyle(string $style): self;

    public function getUrl(mixed $row): ?string;

    public function getTarget(): string;

    public function getName(): ?string;

    public function getIcon(): ?string;

    /**
     * @return array{name: string, params: mixed}|null
     */
    public function getEvent(mixed $row): ?array;

    public function getStyle(): ?string;

    public function getIconStyle(): ?string;

    public function isHidden(mixed $row): bool;

    public function isDisabled(mixed $row): bool;

    public function isWireNavigate(): bool;

    public function hasName(): bool;

    public function hasIcon(): bool;
}
