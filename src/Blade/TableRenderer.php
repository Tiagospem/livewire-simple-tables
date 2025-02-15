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

final readonly class TableRenderer
{
    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    public function render(TableData $table, array $theme): string
    {
        return View::make('simple-tables::table.table', [
            'header'     => $this->renderHeader($table, $theme),
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
    private function renderHeader(TableData $table, array $theme): string
    {
        return View::make('simple-tables::table.partials.table-header', [
            'columns'     => $table->columns,
            'trStyle'     => theme($theme, 'table.tr'),
            'thStyle'     => theme($theme, 'table.th'),
            'thLastStyle' => theme($theme, 'table.th_last'),
            'hasAction'   => $table->actionBuilder->hasAction(),
            'actionName'  => $table->actionBuilder->getActionColumnName(),
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

        return $rows->map(fn($row): string => $this->renderRow($table, $row, $theme))->implode('');
    }

    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    private function renderRow(TableData $table, mixed $row, array $theme): string
    {
        $contentParser = new ContentParser($table, $row, $theme);

        $hasActions = $table->actionBuilder->hasAction();

        return View::make('simple-tables::table.partials.table-row', [
            'rowContent' => $contentParser->mapFieldsWithContent(),
            'action'     => $hasActions ? $this->renderActionBuilder($table, $row, $theme) : null,
            'trStyle'    => $contentParser->getMutedRowStyle(),
            'tdStyle'    => theme($theme, 'table.td'),
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

    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    private function renderActionBuilder(TableData $table, mixed $row, array $theme): string
    {
        return View::make('simple-tables::table.partials.action-builder', [
            'actionBuilder'            => $table->actionBuilder,
            'row'                      => $row,
            'hasName'                  => $table->actionBuilder->hasName(),
            'hasView'                  => $table->actionBuilder->hasView(),
            'hasIcon'                  => $table->actionBuilder->hasIcon(),
            'hasDropdown'              => $table->actionBuilder->hasDropdown(),
            'view'                     => $table->actionBuilder->getView($row),
            'isDisabled'               => $table->actionBuilder->isDisabled($row),
            'dropdownOptions'          => $table->actionBuilder->getActionOptions(), //move callback logic from blade to a class
            'defaultOptionIcon'        => $table->actionBuilder->getDefaultOptionIcon(),
            'buttonStyle'              => $table->actionBuilder->getStyle(),
            'iconStyle'                => $table->actionBuilder->getIconStyle(),
            'buttonIcon'               => $table->actionBuilder->getIcon(),
            'buttonName'               => $table->actionBuilder->getName(),
            'buttonUrl'                => $table->actionBuilder->getUrl($row),
            'buttonTarget'             => $table->actionBuilder->getTarget(),
            'buttonEvent'              => $table->actionBuilder->getEvent($row),
            'themeActionButtonStyle'   => theme($theme, 'action.button'),
            'themeDropdownOptionStyle' => theme($theme, 'dropdown.option'),
            'themeDropdownStyle'       => theme($theme, 'dropdown.content'),
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
