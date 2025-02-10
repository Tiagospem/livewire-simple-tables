@props([
    'hasDropdown' => false,
    'dropdownOptions' => [],
    'defaultOptionIcon' => null,
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
                        $visibleOptions = collect($dropdownOptions)->filter(function ($dropdownOption) use ($row) {
                            if ($dropdownOption->isDivider() && $dropdownOption->hasDividerOptions()) {
                                return !$dropdownOption->isHidden($row);
                            }
                            return !$dropdownOption->isHidden($row);
                        });
                    @endphp

                    @foreach ($visibleOptions as $option)
                        @php
                            $optionDisabled = $option->isDisabled($row);

                            $clickEvent = [
                                'url' => $option->getUrl($row),
                                'target' => $option->getTarget(),
                                'event' => $option->getEvent($row),
                                'disabled' => $optionDisabled,
                            ];

                            $iconStyle = $option->getIconStyle();
                            $buttonStyle = $option->getStyle();
                        @endphp
                        @if ($option->isDivider() && $option->hasDividerOptions())
                            <div class="border-t border-b border-gray-100">
                                @foreach ($option->getDividerOptions() as $dividerOption)
                                    @php
                                        $dividerDisabled = $dividerOption->isDisabled($row);

                                        $clickEvent = [
                                            'url' => $dividerOption->getUrl($row),
                                            'target' => $dividerOption->getTarget(),
                                            'event' => $dividerOption->getEvent($row),
                                            'disabled' => $dividerDisabled,
                                        ];

                                        $iconStyle = $dividerOption->getIconStyle();
                                        $buttonStyle = $option->getStyle();
                                    @endphp
                                    <x-simple-tables::dropdown-option
                                        title="{{ $dividerOption->getName() }}"
                                        icon="{{ $dividerOption->getIcon() ?? $defaultOptionIcon }}"
                                        :disabled="$optionDisabled || $dividerDisabled"
                                        :$clickEvent
                                        :$iconStyle
                                        :$buttonStyle
                                    />
                                @endforeach
                            </div>
                        @else
                            <x-simple-tables::dropdown-option
                                title="{{ $option->getName() }}"
                                icon="{{ $option->getIcon() ?? $defaultOptionIcon }}"
                                :disabled="$optionDisabled"
                                :$clickEvent
                                :$iconStyle
                                :$buttonStyle
                            />
                        @endif
                    @endforeach
                </div>
            </div>
        </template>
    @endif
</div>
