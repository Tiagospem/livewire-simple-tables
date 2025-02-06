<div class="px-4 sm:px-6 lg:px-8">
    @if (!isset($data['columns']))
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
                    <div class="overflow-hidden shadow-sm ring-1 ring-black/5 sm:rounded-lg">
                        @php
                            $tableClass = theme($theme, 'table.content');
                            $theadClass = theme($theme, 'table.thead');
                            $tbodyClass = theme($theme, 'table.tbody');
                            $trClass = theme($theme, 'table.tr');
                            $tdClass = theme($theme, 'table.td');
                            $thClass = theme($theme, 'table.th');
                            $thLastClass = theme($theme, 'table.th_last');
                            $tdNoRecordsClass = theme($theme, 'table.td_no_records');
                        @endphp

                        <x-simple-tables::table :class="$tableClass">
                            <x-simple-tables::thead :class="$theadClass">
                                <x-simple-tables::tr :class="$trClass">
                                    @foreach($data['columns'] as $column)
                                        <x-simple-tables::th :class="$thClass">
                                            {{ $column['title'] }}
                                        </x-simple-tables::th>

                                        @if ($loop->last)
                                            <x-simple-tables::th :class="$thLastClass">
                                                <span class="sr-only">action</span>
                                            </x-simple-tables::th>
                                        @endif
                                    @endforeach
                                </x-simple-tables::tr>
                            </x-simple-tables::thead>

                            <x-simple-tables::tbody :class="$tbodyClass">
                                @php
                                    $modifiers = $data['modifiers'];
                                    $styleModifier = $data['styleModifier'];
                                @endphp

                                @forelse ($data['rows'] as $row)
                                    @php
                                        $parsedStyles = parseStyle($styleModifier, $row, $theme);
                                        $dynamicParsedTrClass = $parsedStyles['trStyle'];
                                        $dynamicParsedTdClass = $parsedStyles['tdStyle'];
                                    @endphp

                                    <x-simple-tables::tr :class="$dynamicParsedTrClass">
                                        @foreach($data['columns'] as $column)
                                            @php
                                                $parsedData = parseData($modifiers, $column, $row, $theme, $dynamicParsedTdClass);
                                                $dynamicTdStyle = $parsedData['dynamicTdStyle'];
                                            @endphp

                                            <x-simple-tables::td :class="$dynamicTdStyle">
                                                {{ $parsedData['content'] }}
                                            </x-simple-tables::td>

                                            @if ($loop->last)
                                                <x-simple-tables::td :class="theme($theme, 'table.td_last')">
                                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">action</a>
                                                </x-simple-tables::td>
                                            @endif
                                        @endforeach
                                    </x-simple-tables::tr>
                                @empty
                                    <x-simple-tables::tr :class="$trClass">
                                        <x-simple-tables::td colspan="9999" :class="$tdNoRecordsClass">
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
