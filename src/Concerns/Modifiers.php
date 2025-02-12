<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use Closure;
use Exception;
use ReflectionFunction;

final class Modifiers
{
    /**
     * @var array<string, array{
     *     callback: Closure,
     *     numberOfParameters: int,
     *     customTdStyle?: string|null,
     *     customTdStyleRule?: Closure|null,
     *     replaceStyle?: bool
     * }>
     */
    public array $fields = [];

    /**
     * @param  array<string, mixed>  $customParams
     */
    public function modify(
        string $column,
        ?Closure $callback = null,
        ?string $view = null,
        string $rowName = 'row',
        ?string $tdStyle = null,
        ?Closure $columnRule = null,
        bool $replaceStyle = false,
        array $customParams = [],
    ): self {
        if (blank($callback) && blank($view)) {
            return $this;
        }

        if (filled($view)) {
            $callback = $this->createViewCallback(
                view: $view,
                rowName: $rowName,
                customParams: $customParams,
            );
        }

        /** @var Closure $callback */
        $numberOfParameters = $this->getNumberOfParameters($callback);

        $this->fields[$column] = [
            'callback'           => $callback,
            'numberOfParameters' => $numberOfParameters,
            'customTdStyle'      => $tdStyle,
            'customTdStyleRule'  => $columnRule,
            'replaceStyle'       => $replaceStyle,
        ];

        return $this;
    }

    public function getCustomTdStyleRule(string $field, mixed $row): ?string
    {
        if (isset($this->fields[$field]['customTdStyleRule'])) {
            $result = $this->fields[$field]['customTdStyleRule']->__invoke($row);

            return is_string($result) || null === $result ? $result : null;
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $customParams
     */
    private function createViewCallback(string $view, string $rowName, array $customParams): Closure
    {
        return fn(string $_, mixed $row) => view($view, [$rowName => $row, ...$customParams]);
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
