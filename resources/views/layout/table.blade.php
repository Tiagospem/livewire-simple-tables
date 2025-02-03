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
                        <x-simple-tables::table>
                            <x-simple-tables::thead>
                                <x-simple-tables::tr>
                                    @foreach($data['columns'] as $column)
                                        <x-simple-tables::th>
                                            {{ $column['title'] }}
                                        </x-simple-tables::th>

                                        @if($loop->last)
                                            <x-simple-tables::th class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">action</span>
                                            </x-simple-tables::th>
                                        @endif
                                    @endforeach
                                </x-simple-tables::tr>
                            </x-simple-tables::thead>
                            <x-simple-tables::tbody>
                                @forelse($data['rows'] as $row)
                                    <x-simple-tables::tr>
                                        @foreach($data['columns'] as $column)
                                            <x-simple-tables::td>
                                                {{ data_get($row, $column['field']) }}
                                            </x-simple-tables::td>

                                            @if($loop->last)
                                                <x-simple-tables::td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">action</a>
                                                </x-simple-tables::td>
                                            @endif
                                        @endforeach
                                    </x-simple-tables::tr>
                                @empty
                                    <x-simple-tables::tr>
                                        <x-simple-tables::td colspan="9999">
                                            <div class="text-center">
                                                <p class="text-sm text-gray-500">
                                                    {{ __('simple-tables::table.no-records') }}
                                                </p>
                                            </div>
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