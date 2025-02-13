<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables;

use Closure;
use Exception;
use ReflectionFunction;
use ReflectionNamedType;

final class Field
{
    private string $field;

    /**
     * @var array{
     *     callback: Closure|null,
     *     numberOfParameters: int,
     *     parameterType: string,
     * }
     */
    private array $mutation = [
        'callback'           => null,
        'numberOfParameters' => 1,
        'parameterType'      => '',
    ];

    /**
     * @var array{
     *     callback: Closure|null,
     *     numberOfParameters: int,
     *     parameterType: string,
     * }
     */
    private array $styleRule = [
        'callback'           => null,
        'numberOfParameters' => 1,
        'parameterType'      => '',
    ];

    private ?string $style = null;

    public static function name(string $field): self
    {
        $instance = new self();

        $instance->field = $field;

        return $instance;
    }

    /**
     * @return $this
     */
    public function view(): self
    {
        return $this;
    }

    public function mutate(Closure $callback): self
    {
        $this->prepareCallback('mutation', $callback);

        return $this;
    }

    public function style(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function styleRule(Closure $callback): self
    {
        $this->prepareCallback('styleRule', $callback);

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

    /**
     * @return object{
     *     callback: Closure|null,
     *     numberOfParameters: int,
     *     parameterType: string,
     * }
     */
    public function getMutation(): object
    {
        return (object) $this->mutation;
    }

    /**
     * @return object{
     *     callback: Closure|null,
     *     numberOfParameters: int,
     *     parameterType: string,
     * }
     */
    public function getStyleRule(): object
    {
        return (object) $this->styleRule;
    }

    private function prepareCallback(string $property, Closure $callback): void
    {
        $this->{$property}['callback']           = $callback;
        $this->{$property}['numberOfParameters'] = $this->getNumberOfParameters($callback);
        $this->{$property}['parameterType']      = $this->getParameterType($callback);
    }

    private function getParameterType(Closure $callback): string
    {
        try {
            $reflection = new ReflectionFunction($callback);
            $parameters = $reflection->getParameters();

            $type = $parameters[0]->getType();

            return $type instanceof ReflectionNamedType ? $type->getName() : 'mixed';
        } catch (Exception) {
            return '';
        }

    }

    private function getNumberOfParameters(Closure $callback): int
    {
        try {
            $reflection = new ReflectionFunction($callback);

            return $reflection->getNumberOfParameters();
        } catch (Exception) {
            return 1;
        }
    }
}
