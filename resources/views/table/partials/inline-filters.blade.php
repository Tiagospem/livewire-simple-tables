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

    @includeWhen($totalFiltersSelected, 'simple-tables::table.partials.clean-filters')
</div>
