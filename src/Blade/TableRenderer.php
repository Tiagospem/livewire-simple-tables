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
    public function __construct(
        private SimpleTableComponent $component,
        private TableData $table,
        private array $theme,
    ) {}

    public function render(): string
    {
        return View::make('simple-tables::table.table', [
            'header'               => $this->renderHeader(),
            'body'                 => $this->renderBody(),
            'pagination'           => $this->renderPagination(),
            'filters'              => $this->component->getFilters(),
            'totalFiltersSelected' => $this->component->getTotalFiltersSelected(),
            'showSearch'           => $this->table->showSearch,
            'tableStyle'           => theme($this->theme, 'table.content'),
            'bodyStyle'            => theme($this->theme, 'table.body'),
        ])->render();
    }

    private function renderHeader(): string
    {
        return View::make('simple-tables::table.partials.table-header', [
            'columns'           => $this->getVisibleColumns(),
            'sortBy'            => $this->component->sortBy,
            'sortDirection'     => $this->component->sortDirection,
            'sortableIcons'     => $this->component->sortableIcons(),
            'trHeaderStyle'     => theme($this->theme, 'table.tr_header'),
            'thStyle'           => theme($this->theme, 'table.th'),
            'thLastStyle'       => theme($this->theme, 'table.th_last'),
            'sortIconStyle'     => theme($this->theme, 'table.sort_icon'),
            'hasAction'         => $this->table->actionBuilder->hasActions(),
            'detailViewEnabled' => $this->detailViewEnabled(),
        ])->render();
    }

    /**
     * @return Collection<int, mixed>
     */
    private function getVisibleColumns(): Collection
    {
        return collect($this->table->columns)->filter(fn($column): bool => $column->isVisible());
    }

    private function renderBody(): string
    {
        return $this->getRowsCollection()->isEmpty()
            ? $this->renderEmptyRow()
            : $this->renderRows();
    }

    private function renderRows(): string
    {
        return $this->getRowsCollection()->map(fn($row): string => $this->renderRow($row))->implode('');
    }

    private function renderRow(mixed $row): string
    {
        $contentParser = new ContentParser($this->table, $row, $this->theme);
        $rowId         = data_get($row, $this->component->primaryKey);

        $shouldShowDetail = $this->shouldShowDetail($rowId);

        return View::make('simple-tables::table.partials.table-row', [
            'rowContent'        => $contentParser->mapFieldsWithContent(),
            'detailViewEnabled' => $this->detailViewEnabled(),
            'shouldShowDetail'  => $shouldShowDetail,
            'detailView'        => $shouldShowDetail ? $this->renderDetailView($row) : '',
            'rowId'             => $rowId,
            'trStyle'           => $contentParser->getMutedRowStyle(),
            'tdStyle'           => theme($this->theme, 'table.td'),
        ])->render();
    }

    private function detailViewEnabled(): bool
    {
        return filled($this->component->detailView);
    }

    private function shouldShowDetail(mixed $rowId): bool
    {
        return $this->detailViewEnabled() && in_array($rowId, $this->component->expandedRows, true);
    }

    private function renderDetailView(mixed $row): string
    {
        return View::make($this->component->detailView, [
            'row' => $row,
        ])->render();
    }

    private function renderEmptyRow(): string
    {
        return View::make('simple-tables::table.partials.table-empty-row', [
            'trStyle' => theme($this->theme, 'table.tr'),
            'tdStyle' => theme($this->theme, 'table.td_no_records'),
        ])->render();
    }

    private function renderPagination(): string
    {
        $rows = $this->table->rows;

        $totalRows = $rows instanceof LengthAwarePaginatorContract ? $rows->total() : $rows->count();

        $hasPagination = $totalRows > $this->component->perPage;

        return View::make('simple-tables::table.partials.pagination', [
            'paginator'     => $this->getPaginator(),
            'hasPagination' => $hasPagination,
            'isStick'       => $this->component->stickyPagination,
            'stickyStyle'   => theme($this->theme, 'pagination.sticky'),
            'style'         => theme($this->theme, 'pagination.container'),
        ])->render();
    }

    private function getPaginator(): string
    {
        return ($this->table->paginated && $this->table->rows instanceof LengthAwarePaginator)
            ? $this->table->rows->links()->toHtml()
            : '';
    }

    /**
     * @return Collection<int, mixed>
     */
    private function getRowsCollection(): Collection
    {
        return $this->convertRowsToCollection($this->table->rows);
    }

    /**
     * @param  LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>|Collection<int, mixed>|Builder<Model>  $rows
     * @return Collection<int, mixed>
     */
    private function convertRowsToCollection(mixed $rows): Collection
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
