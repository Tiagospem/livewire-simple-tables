@props(['hasDropdown' => false])
<div class="relative inline-block text-left">
    {{ $actionButton }}

    @if ($hasDropdown)
        <template x-teleport="body">
            <div
                x-ref="dropdownPanel"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                x-cloak
                x-show="dropdownOpen"
                x-on:click.away="dropdownOpen = false"
                class="z-40 w-56 fixed divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                role="menu"
                aria-orientation="vertical"
                aria-labelledby="menu-button"
                tabindex="-1"
            >
                <x-simple-tables::dropdown-option
                    title="Test"
                    icon="heroicon-o-bolt"
                />
            </div>
        </template>
    @endif
</div>
