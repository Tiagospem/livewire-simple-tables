<tr class="{{ $trStyle }}">
    @foreach ($rowContent as $row)
        @if ($loop->first && $detailViewEnabled)
            <td class="{{ $tdStyle }}">
                <a class="cursor-pointer" wire:click="toggleRowDetail({{ $rowId }})">
                    <x-simple-tables::svg.chevron-right class="size-4" />
                </a>
            </td>
        @endif

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
