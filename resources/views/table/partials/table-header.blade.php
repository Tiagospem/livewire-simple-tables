<tr class="{{ $trStyle }}">
    @foreach ($columns as $column)
        <th class="{{ mergeStyle($thStyle, $column->getStyle()) }}">
            @if ($column->isActionColumn())
                {{ $column->getTitle() }}
            @else
                <div>
                    <span>{{ $column->getTitle() }}</span>

                    @if ($column->isSortable())
                        <x-simple-tables::sortable
                            :$sortableIcons
                            :$sortIconStyle
                            :$sortBy
                            :$sortDirection
                            :column="$column->getRealKey()"
                        />
                    @endif
                </div>
            @endif
        </th>
    @endforeach
</tr>
