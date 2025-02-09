@props([
    'hasDropdown' => false,
    'actionOptions' => [],
    'defaultDropdownOptionIcon' => null,
    'row',
])
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
                class="z-40 w-56 fixed overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                role="menu"
                aria-orientation="vertical"
                aria-labelledby="menu-button"
                tabindex="-1"
            >
                <div class="max-h-[300px] overflow-auto custom-scrollbar">
                    @php
                        $visibleOptions = collect($actionOptions)->filter(function ($actionOption) use ($row) {
                            if ($actionOption->getIsDivider() && filled($actionOption->getDividerOptions())) {
                                return !$actionOption->getIsHidden($row);
                            }
                            return !$actionOption->getIsHidden($row);
                        });
                    @endphp

                    @foreach ($visibleOptions as $actionOption)
                        @php
                            $optionDisabled = $actionOption->getIsDisabled($row);
                        @endphp
                        @if ($actionOption->getIsDivider() && filled($actionOption->getDividerOptions()))
                            <div class="border-t border-b border-gray-100">
                                @foreach ($actionOption->getDividerOptions() as $dividerOption)
                                    @php
                                        $optionDividerDisabled = $dividerOption->getIsDisabled($row);
                                    @endphp
                                    <x-simple-tables::dropdown-option
                                        title="{{ $dividerOption->getName() }}"
                                        icon="{{ $dividerOption->getIcon() ?? $defaultDropdownOptionIcon }}"
                                        :disabled="$optionDisabled || $optionDividerDisabled"
                                    />
                                @endforeach
                            </div>
                        @else
                            <x-simple-tables::dropdown-option
                                title="{{ $actionOption->getName() }}"
                                icon="{{ $actionOption->getIcon() ?? $defaultDropdownOptionIcon }}"
                                :disabled="$optionDisabled"
                            />
                        @endif
                    @endforeach
                </div>
            </div>
        </template>
    @endif
</div>
