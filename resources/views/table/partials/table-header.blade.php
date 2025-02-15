<tr class="{{ $trStyle }}">
    @foreach ($columns as $column)
        <th class="{{ mergeStyle($thStyle, $column->getStyle()) }}">
            <div>
                {{ $column->getTitle() }}
                @if($column->isSortable())
                    <x-simple-tables::sortable :$sortableIcons :$sortIconStyle :$sortBy :$sortDirection :column="$column->getRealKey()" />
                @endif
            </div>
        </th>
    @endforeach

    @if ($hasAction)
        <th class="{{ $thLastStyle }}">{{ $actionName }}</th>
    @endif
</tr>
