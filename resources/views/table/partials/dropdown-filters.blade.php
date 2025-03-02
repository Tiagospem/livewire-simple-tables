<div
    x-data="{
        open: false,
    }"
    class="relative"
>
    <div>
        <div
            x-bind:class="{ '!bg-slate-500 !text-white transition': open }"
            x-on:click="open = !open"
            class="rounded-lg text-slate-500 bg-white shadow-sm p-1 mb-4 ring-1 ring-slate-100 cursor-pointer relative"
        >
            <x-simple-tables::svg.funnel class="h-6 w-6" />

            <x-simple-tables::circle-badge>{{ $totalFiltersSelected }}</x-simple-tables::circle-badge>
        </div>
    </div>

    <div
        x-cloak
        x-show="open"
        x-on:click.away="open = false"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="-translate-y-2"
        x-transition:enter-end="translate-y-0"
        class="overflow-auto shadow-lg ring-1 ring-black/5 focus:outline-none bg-white p-3 rounded-lg absolute z-50 w-[350px] top-9 flex flex-col gap-2"
    >
        @foreach ($filters as $filter)
            {!! $filter->render() !!}
        @endforeach

        @includeWhen($totalFiltersSelected, 'simple-tables::table.partials.clean-filters')
    </div>
</div>
