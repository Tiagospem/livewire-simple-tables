<th class="{{ mergeStyle($thStyle, 'relative') }}">
    <div
        class="flex"
        x-data="{
            open: false,
        }"
    >
        <x-simple-tables::checkbox x-on:click="selectAll()" />

        <x-simple-tables::svg.ellipsis-vertical
            @class([
                'size-5',
                'cursor-pointer animate-pulse' => $hasSelectedIds,
                'text-gray-300 disabled pointer-events-none' => !$hasSelectedIds,
            ])
            @if ($hasSelectedIds) x-on:click="open = !open" @endif
        />

        <div
            x-cloak
            x-show="open"
            x-on:click.away="open = false"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="-translate-y-2"
            x-transition:enter-end="translate-y-0"
            class="{{ $themeDropdownStyle }}"
        >
            @foreach($bulkActions as $action)
                <a class="{{ mergeStyle($themeDropdownOptionStyle) }}">
                    @if ($action->hasIcon())
                        <x-dynamic-component
                            :component="$action->getIcon()"
                            @class(['mr-3 size-4.5 text-slate-500'])
                        />
                    @endif

                    {{ $action->getName() }} ({{ count($action->getSelectedIds()) }})
                </a>
            @endforeach
        </div>
    </div>
</th>
