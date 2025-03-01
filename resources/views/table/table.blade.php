<div x-data="clickEvent">
    <div class="flex justify-between items-end">
        @includeWhen($hasFilters && !$inlineFilters, 'simple-tables::table.partials.dropdown-filters')

        @include('simple-tables::table.partials.search')
    </div>

    @includeWhen($hasFilters && $inlineFilters, 'simple-tables::table.partials.inline-filters')

    <div class="overflow-auto custom-scrollbar rounded-lg shadow-sm ring-1 min-w-full ring-black/5">
        <div class="overflow-auto align-middle w-full">
            <table class="{{ $tableStyle }}">
                <thread>
                    {!! $header !!}
                </thread>
                <tbody class="{{ $bodyStyle }}">
                    {!! $body !!}
                </tbody>
            </table>
        </div>
    </div>

    {!! $pagination !!}
</div>
