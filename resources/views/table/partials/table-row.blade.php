<tr class="{{ $trStyle }}">
    @foreach ($rowContent as $row)
        @includeWhen($loop->first && $detailViewEnabled, 'simple-tables::table.partials.detail-icon')

        <td class="{{ $row->style }}">
            {!! $row->content !!}
        </td>
    @endforeach
</tr>

@if($shouldShowDetail)
    <tr>
        <td colspan="999" class="border-y">
            <div class="p-4 bg-white">
                {!! $detailView !!}
            </div>
        </td>
    </tr>
@endif
