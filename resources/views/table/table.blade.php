<div x-data="clickEvent">
    @include('simple-tables::table.partials.search')
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

    <div class="mt-4">
        {!! $pagination !!}
    </div>
</div>
