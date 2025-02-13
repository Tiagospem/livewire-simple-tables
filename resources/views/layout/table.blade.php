<div class="px-4">
    @if (!isset($data['columns']))
        <p>Please define the columns</p>
    @else
        @if ($showSearch)
            <div class="flex justify-end mt-4">
                <div class="w-[300px]">
                    <x-simple-tables::input-search wire:model.live.debounce="search" />
                </div>
            </div>
        @endif

        <div class="mt-4">
            <div class="overflow-auto custom-scrollbar rounded-lg shadow-sm ring-1 min-w-full ring-black/5">
                <div class="overflow-auto align-middle w-full">
                    @php
                        $themeTableClass = theme($theme, 'table.content');
                        $themeTheadClass = theme($theme, 'table.thead');
                        $themeTbodyClass = theme($theme, 'table.tbody');
                        $themeTrClass = theme($theme, 'table.tr');
                        $themeThClass = theme($theme, 'table.th');
                        $themeThLastClass = theme($theme, 'table.th_last');
                        $themeTdNoRecordsClass = theme($theme, 'table.td_no_records');
                        $themeDropdownClass = theme($theme, 'dropdown.content');
                        $themeDropdownOptionClass = theme($theme, 'dropdown.option');

                        $themeButtonActionClass = theme($theme, 'action.button');

                        $actionBuilder = $data['actions'];

                        $hasActions = $actionBuilder->hasAction();
                    @endphp

                    <x-simple-tables::table :class="$themeTableClass">
                        <x-simple-tables::thead :class="$themeTheadClass">
                            <x-simple-tables::tr :class="$themeTrClass">
                                @foreach ($data['columns'] as $column)
                                    <x-simple-tables::th :class="$themeThClass">
                                        {{ $column->getTitle() }}
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
                                $mutations = $data['mutations'];
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
                                                $mutations,
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

                                        @if ($loop->last && $hasActions && !$actionBuilder->isHidden($row))
                                            <x-simple-tables::td :class="$themeThLastClass">
                                                <x-simple-tables::action-button
                                                    :$actionBuilder
                                                    :$themeButtonActionClass
                                                    :$themeDropdownClass
                                                    :$themeDropdownOptionClass
                                                    :$row
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
            @if ($paginated)
                <div class="mt-4">
                    {{ $data['rows']->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
