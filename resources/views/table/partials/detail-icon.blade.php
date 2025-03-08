<td class="{{ $tdStyle }}">
    <div
        wire:loading.remove
        wire:target="toggleRowDetail({{ $rowId }})"
    >
        <a wire:click="toggleRowDetail({{ $rowId }})">
            <x-simple-tables::svg.chevron-right @class([
                'size-4 transition cursor-pointer',
                'transform rotate-90' => $shouldShowDetail,
            ]) />
        </a>
    </div>
    <div
        wire:loading
        wire:target="toggleRowDetail({{ $rowId }})"
    >
        <x-simple-tables::svg.spinner2 @class(['h-4 w-4 transition']) />
    </div>
</td>
