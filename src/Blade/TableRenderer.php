<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Blade;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use TiagoSpem\SimpleTables\Dto\TableData;
use TiagoSpem\SimpleTables\SimpleTableComponent;

final readonly class TableRenderer
{
    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    public function render(TableData $table, SimpleTableComponent $component, array $theme): string
    {
        return View::make('simple-tables::table.table', [
            'header'     => $this->renderHeader(
                $table,
                $component,
                $theme,
            ),
            'body'       => $this->renderBody($table, $theme),
            'pagination' => $this->renderPagination($table),
            'showSearch' => $table->showSearch,
            'tableStyle' => theme($theme, 'table.content'),
            'bodyStyle'  => theme($theme, 'table.body'),
        ])->render();
    }

    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    private function renderHeader(TableData $table, SimpleTableComponent $component, array $theme): string
    {
        return View::make('simple-tables::table.partials.table-header', [
            'columns'       => collect($table->columns)->filter(fn($column): bool => $column->isVisible()),
            'sortBy'        => $component->sortBy,
            'sortDirection' => $component->sortDirection,
            'sortableIcons' => $component->sortableIcons(),
            'trStyle'       => theme($theme, 'table.tr'),
            'thStyle'       => theme($theme, 'table.th'),
            'thLastStyle'   => theme($theme, 'table.th_last'),
            'sortIconStyle' => theme($theme, 'table.sort_icon'),
            'hasAction'     => $table->actionBuilder->hasActions(),
            'actionName'    => $table->actionBuilder->getActionColumnName(),
        ])->render();
    }

    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    private function renderBody(TableData $table, array $theme): string
    {
        $rows = $this->getRowsCollection($table->rows);

        return $rows->isEmpty()
            ? $this->renderEmptyRow($theme)
            : $this->renderRows($table, $theme);
    }

    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    private function renderRows(TableData $table, array $theme): string
    {
        $rows = $this->getRowsCollection($table->rows);

        $table->actionBuilder->hasActions();

        return $rows->map(fn($row): string => $this->renderRow($table, $row, $theme))->implode('');
    }

    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    private function renderRow(TableData $table, mixed $row, array $theme): string
    {
        $contentParser = new ContentParser($table, $row, $theme);

        return View::make('simple-tables::table.partials.table-row', [
            'rowContent'  => $contentParser->mapFieldsWithContent(),
            'trStyle'     => $contentParser->getMutedRowStyle(),
            'tdStyle'     => theme($theme, 'table.td'),
            'actionStyle' => mergeStyle(theme($theme, 'table.td_last'), $table->actionBuilder->getColumnStyle()),
        ])->render();
    }

    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    private function renderEmptyRow(array $theme): string
    {
        return View::make('simple-tables::table.partials.table-empty-row', [
            'trStyle' => theme($theme, 'table.tr'),
            'tdStyle' => theme($theme, 'table.td_no_records'),
        ])->render();
    }

    private function renderPagination(TableData $table): string
    {
        return $table->paginated && $table->rows instanceof LengthAwarePaginator
            ? $table->rows->links()->toHtml()
            : '';
    }

    /**
     * @param  LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>|Collection<int, mixed>|Builder<Model> $rows
     * @return Collection<int, mixed>
     */
    private function getRowsCollection(mixed $rows): Collection
    {
        if ($rows instanceof Collection) {
            return $rows;
        }

        if ($rows instanceof LengthAwarePaginator) {
            return $rows->getCollection();
        }

        if (method_exists($rows, 'get')) {
            /** @var array<int, mixed> $results */
            $results = $rows->get();

            return collect($results);
        }

        return collect();
    }
}
