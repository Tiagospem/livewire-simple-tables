<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use Closure;
use TiagoSpem\SimpleTables\Dto\TableStyleData;

final class TableRowStyle
{
    /**
     * @var array<TableStyleData>
     */
    private array $rowCallbacks = [];

    public function style(Closure|string $callback, bool $overrideRowStyle = false): self
    {
        $this->rowCallbacks[] = new TableStyleData($callback, $overrideRowStyle);

        return $this;
    }

    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    public function getRowStyle(mixed $row, array $theme): string
    {
        $currentStyle = theme($theme, 'table.tr');

        foreach ($this->rowCallbacks as $callback) {
            $style = $callback->callback;

            if (is_callable($style)) {
                $style = $style($row);
            }

            $currentStyle = $callback->overrideStyle ? $style : mergeStyle($currentStyle, $style);
        }

        return $currentStyle;
    }
}
