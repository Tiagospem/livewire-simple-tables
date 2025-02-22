<div x-data="clickEvent">
    <div class="flex justify-between items-end">
        @includeWhen(count($filters) > 0, 'simple-tables::table.partials.filters')

        @include('simple-tables::table.partials.search')
    </div>

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
