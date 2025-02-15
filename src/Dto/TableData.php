<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Dto;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\TableRowStyle;
use TiagoSpem\SimpleTables\Field;

final readonly class TableData
{
    /**
     * @param  array<Column>  $columns
     * @param  array<Field>  $mutations
     * @param  Builder<Model>|Collection<int, mixed>|LengthAwarePaginator<int, mixed>|LengthAwarePaginatorContract<int, mixed>  $rows
     */
    public function __construct(
        public array $columns,
        public array $mutations,
        public TableRowStyle $tableRowStyle,
        public ActionBuilder $actionBuilder,
        public Builder|Collection|LengthAwarePaginator|LengthAwarePaginatorContract $rows,
        public bool $paginated,
        public bool $showSearch,
    ) {}
}
