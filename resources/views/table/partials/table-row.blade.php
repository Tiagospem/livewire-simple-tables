<tr
    class="{{ $trStyle }}"
    wire:key="{{ 'id_' . $rowId }}"
>
    @foreach ($rowContent as $row)
        @includeWhen($loop->first && $detailViewEnabled, 'simple-tables::table.partials.detail-icon')

        @includeWhen($loop->first && $hasBulkActions, 'simple-tables::table.partials.checkbox-icon')

        <td class="{{ $tdStyle }}">
            <div class="{{ $row->style }}">
                {!! $row->content !!}
            </div>
        </td>
    @endforeach
</tr>

@if ($shouldShowDetail)
    <tr>
        <td
            colspan="999"
            class="border-y"
        >
            <div class="bg-white">
                {!! $detailView !!}
            </div>
        </td>
    </tr>
@endif
