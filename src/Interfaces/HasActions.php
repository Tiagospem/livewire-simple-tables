<?php

namespace TiagoSpem\SimpleTables\Interfaces;

use Closure;
use TiagoSpem\SimpleTables\Enum\Target;

interface HasActions
{
    public function href(Closure $href, Target $target): self;

    public function event(string $name, Closure $params): self;

    public function disabled(Closure|bool $disabled = true): self;

    public function hidden(Closure|bool $hidden = true): self;

    public function iconStyle(string $style): self;

    public function buttonStyle(string $style): self;

    public function getUrl(mixed $row): ?string;

    public function getTarget(): string;

    public function getName(): ?string;

    public function getIcon(): ?string;

    /**
     * @return null|array{
     *     name: string,
     *     params?: array<string, mixed>,
     * }
     */
    public function getEvent(mixed $row): ?array;

    public function getStyle(): ?string;

    public function getIconStyle(): ?string;

    public function isHidden(mixed $row): bool;

    public function isDisabled(mixed $row): bool;

    public function hasName(): bool;

    public function hasIcon(): bool;
}
