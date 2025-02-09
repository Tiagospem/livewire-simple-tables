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
                            $themeTableClass = theme($theme, 'table.content');
                            $themeTheadClass = theme($theme, 'table.thead');
                            $themeTbodyClass = theme($theme, 'table.tbody');
                            $themeTrClass = theme($theme, 'table.tr');
                            $themeThClass = theme($theme, 'table.th');
                            $themeThLastClass = theme($theme, 'table.th_last');
                            $themeTdNoRecordsClass = theme($theme, 'table.td_no_records');

                            $themeButtonActionClass = theme($theme, 'action.button');

                            $actionBuilder = $data['actions'];

                            $hasActions = $actionBuilder->hasActions();
                        @endphp

                        <x-simple-tables::table :class="$themeTableClass">
                            <x-simple-tables::thead :class="$themeTheadClass">
                                <x-simple-tables::tr :class="$themeTrClass">
                                    @foreach ($data['columns'] as $column)
                                        <x-simple-tables::th :class="$themeThClass">
                                            {{ $column['title'] }}
                                        </x-simple-tables::th>

                                        @if ($loop->last && $hasActions)
                                            <x-simple-tables::th :class="$themeThLastClass">
                                                <span class="sr-only">action</span>
                                            </x-simple-tables::th>
                                        @endif
                                    @endforeach
                                </x-simple-tables::tr>
                            </x-simple-tables::thead>

                            <x-simple-tables::tbody :class="$themeTbodyClass">
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
                                        @foreach ($data['columns'] as $column)
                                            @php
                                                $parsedData = parseData(
                                                    $modifiers,
                                                    $column,
                                                    $row,
                                                    $theme,
                                                    $dynamicParsedTdClass,
                                                );
                                                $dynamicTdStyle = $parsedData['dynamicTdStyle'];
                                            @endphp

                                            <x-simple-tables::td :class="$dynamicTdStyle">
                                                {!! $parsedData['content'] !!}
                                            </x-simple-tables::td>

                                            @if ($loop->last && $hasActions)
                                                <x-simple-tables::td :class="$themeThLastClass">
                                                    <x-simple-tables::action-button
                                                        :actionBuilder="$actionBuilder"
                                                        :themeButtonActionClass="$themeButtonActionClass"
                                                        :row="$row"
                                                    />
                                                </x-simple-tables::td>
                                            @endif
                                        @endforeach
                                    </x-simple-tables::tr>
                                @empty
                                    <x-simple-tables::tr :class="$themeTrClass">
                                        <x-simple-tables::td
                                            colspan="9999"
                                            :class="$themeTdNoRecordsClass"
                                        >
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

        @if ($paginated)
            <div class="mt-4">
                {{ $data['rows']->links() }}
            </div>
        @endif
    @endif
</div>
