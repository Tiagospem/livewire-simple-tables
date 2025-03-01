<div>
    <div @class([
        'grid mt-2',
        'mb-2' => $totalFiltersSelected,
        'mb-6' => !$totalFiltersSelected,
        $filterGridStyle,
    ])>

        @foreach ($filters as $filter)
            {!! $filter->render() !!}
        @endforeach
    </div>

    @if ($totalFiltersSelected)
        <div class="mb-3">
            <a
                wire:click="clearFilters()"
                class="flex items-center text-red-500 font-semibold text-sm cursor-pointer hover:text-red-700"
            >
                <x-simple-tables::svg.trash class="size-4 inline-block" />
                {{ __('simple-tables::table.clean-filters') }}
            </a>
        </div>
    @endif
</div>
