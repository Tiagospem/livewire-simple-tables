<div class="px-4 sm:px-6 lg:px-8">
    @if(!isset($data['columns']))
        <p>Please define the columns</p>
    @else
        <div class="flex justify-end mt-4">
            <div class="w-[300px]">
                <x-simple-tables::input-search wire:model.live.debounce="search" />
            </div>
        </div>

        <div class="mt-4 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                        <x-simple-tables::table :class="theme($theme, 'table.content')">
                            <x-simple-tables::thead :class="theme($theme, 'table.thead')">
                                <x-simple-tables::tr :class="theme($theme, 'table.tr')">
                                    @foreach($data['columns'] as $column)
                                        <x-simple-tables::th :class="theme($theme, 'table.th')">
                                            {{ $column['title'] }}
                                        </x-simple-tables::th>

                                        @if($loop->last)
                                            <x-simple-tables::th :class="theme($theme, 'table.th_last')">
                                                <span class="sr-only">action</span>
                                            </x-simple-tables::th>
                                        @endif
                                    @endforeach
                                </x-simple-tables::tr>
                            </x-simple-tables::thead>
                            <x-simple-tables::tbody :class="theme($theme, 'table.tbody')">
                                @php
                                    $modifiers = $data['modifiers'];
                                    $styleModifier = $data['styleModifier'];
                                @endphp
                                @forelse($data['rows'] as $row)
                                    @php
                                        $style = style($styleModifier, $row)
                                    @endphp
                                    <x-simple-tables::tr :class="theme($theme, 'table.tr', $style['tr'])">
                                        @foreach($data['columns'] as $column)
                                            @php
                                                $parsedData = parseData($modifiers, $column, $row)
                                            @endphp
                                            <x-simple-tables::td :class="theme($theme, 'table.td', $style['td'] ?? $parsedData['tdStyle'])">
                                                {!! e($parsedData['content']) !!}
                                            </x-simple-tables::td>

                                            @if($loop->last)
                                                <x-simple-tables::td :class="theme($theme, 'table.td_last')">
                                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">action</a>
                                                </x-simple-tables::td>
                                            @endif
                                        @endforeach
                                    </x-simple-tables::tr>
                                @empty
                                    <x-simple-tables::tr :class="theme($theme, 'table.tr')">
                                        <x-simple-tables::td colspan="9999" :class="theme($theme, 'table.td_no_records')">
                                            {{ __('simple-tables::table.no-records') }}
                                        </x-simple-tables::td>
                                    </x-simple-tables::tr>
                                @endforelse
                            </x-simple-tables::tbody>
                        </x-simple-tables::table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $data['rows']->links() }}
        </div>
    @endif
</div>