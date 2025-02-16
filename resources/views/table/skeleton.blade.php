<div>
    @if ($showSearch)
        <div class="flex justify-end">
            <div class="w-[300px]">
                <div class="h-10 bg-gray-100 animate-pulse rounded-md">
                </div>
            </div>
        </div>
    @endif
    <div class="mt-4">
        <div class="overflow-auto custom-scrollbar rounded-lg ring-1 min-w-full ring-black/5">
            <div class="overflow-auto align-middle w-full">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-100 animate-pulse">
                        <tr>
                            @foreach (range(0, $columns) as $ignored)
                                <th class="px-3 py-2 text-sm text-transparent">
                                    -
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach (range(1, $perPage) as $ignored)
                            <tr>
                                @foreach (range(0, $columns) as $ignored)
                                    <td class="px-3 py-3">
                                        <div class="w-full h-4 bg-gray-100 animate-pulse rounded-md"></div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
