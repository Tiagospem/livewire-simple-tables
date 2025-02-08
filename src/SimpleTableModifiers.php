<?php

namespace TiagoSpem\SimpleTables;

use Closure;
use Exception;
use ReflectionFunction;

class SimpleTableModifiers
{
    public array $fields = [];

    public function modify(
        string $column,
        ?Closure $callback = null,
        ?string $view = null,
        string $rowName = 'row',
        ?string $tdStyle = null,
        ?Closure $columnRule = null,
        bool $replaceStyle = false,
        array $customParams = []
    ): self {
        if (blank($callback) && blank($view)) {
            return $this;
        }

        if (filled($view)) {
            $callback = $this->createViewCallback(view: $view, rowName: $rowName, customParams: $customParams);
        }

        $numberOfParameters = $this->getNumberOfParameters($callback);

        $this->fields[$column] = [
            'callback' => $callback,
            'numberOfParameters' => $numberOfParameters,
            'customTdStyle' => $tdStyle,
            'customTdStyleRule' => $columnRule,
            'replaceStyle' => $replaceStyle,
        ];

        return $this;
    }

    private function createViewCallback(string $view, string $rowName, array $customParams): Closure
    {
        return fn (string $_, mixed $row) => view($view, [$rowName => $row, ...$customParams]);
    }

    private function getNumberOfParameters(Closure $callback): int
    {
        try {
            $reflection = new ReflectionFunction($callback);

            $numberOfParameters = $reflection->getNumberOfParameters();
        } catch (Exception) {
            $numberOfParameters = 1;
        }

        return $numberOfParameters;
    }
}
