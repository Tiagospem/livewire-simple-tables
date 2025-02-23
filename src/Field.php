<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Closure;
use Illuminate\Support\Facades\View;
use TiagoSpem\SimpleTables\Dto\FieldConfig;

final class Field
{
    private string $rowKey;

    private FieldConfig $mutation;

    /**
     * @var array<FieldConfig>
     */
    private array $styleRules = [];

    /**
     * @var array<string>
     */
    private array $styles = [];

    private function __construct()
    {
        $this->mutation = app(FieldConfig::class);
    }

    public static function key(string $rowKey): self
    {
        $instance         = new self();
        $instance->rowKey = $rowKey;

        return $instance;
    }

    /**
     * @param  array<string, mixed>  $customParams
     */
    public function view(string $view, array $customParams = []): self
    {
        $this->mutation = FieldConfig::fromClosure(fn(object $row) => View::make($view, ['row' => $row, ...$customParams])->render());

        return $this;
    }

    public function mutate(Closure $callback): self
    {
        $this->mutation = FieldConfig::fromClosure($callback);

        return $this;
    }

    public function style(string $style): self
    {
        $this->styles[] = $style;

        return $this;
    }

    public function styleRule(Closure $callback): self
    {
        $this->styleRules[] = FieldConfig::fromClosure($callback);

        return $this;
    }

    public function getRowKey(): string
    {
        return $this->rowKey;
    }

    public function getStyle(): string
    {
        return implode(' ', $this->styles);
    }

    public function getMutation(): FieldConfig
    {
        return $this->mutation;
    }

    /**
     * @return array<FieldConfig>
     */
    public function getStyleRules(): array
    {
        return $this->styleRules;
    }
}
