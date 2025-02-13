<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Closure;
use TiagoSpem\SimpleTables\Dto\FieldConfig;

final class Field
{
    private string $field;
    private FieldConfig $mutation;
    private FieldConfig $styleRule;
    private ?string $style = null;

    private function __construct()
    {
        $this->mutation  = app(FieldConfig::class);
        $this->styleRule = app(FieldConfig::class);
    }

    public static function name(string $field): self
    {
        $instance        = new self();
        $instance->field = $field;

        return $instance;
    }

    /**
     * @param  array<string, mixed>  $customParams
     */
    public function view(string $view, array $customParams = []): self
    {
        $this->mutation = FieldConfig::fromClosure(fn(Object $row) => view($view, ['row' => $row, ...$customParams]));

        return $this;
    }

    public function mutate(Closure $callback): self
    {
        $this->mutation = FieldConfig::fromClosure($callback);
        return $this;
    }

    public function style(string $style): self
    {
        $this->style = $style;
        return $this;
    }

    public function styleRule(Closure $callback): self
    {
        $this->styleRule = FieldConfig::fromClosure($callback);
        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function getMutation(): FieldConfig
    {
        return $this->mutation;
    }

    public function getStyleRule(): FieldConfig
    {
        return $this->styleRule;
    }
}
