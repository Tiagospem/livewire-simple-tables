@props([
    'hasDropdown' => false,
    'dropdownOptions' => [],
    'defaultOptionIcon' => null,
    'themeDropdownOptionStyle',
    'themeDropdownStyle',
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
                class="{{ $themeDropdownStyle }}"
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

                            $event = [
                                'event' => $option->getEvent($row),
                                'disabled' => $optionDisabled,
                            ];

                            $url = $option->getUrl($row);
                            $target = $option->getTarget();

                            $iconStyle = $option->getIconStyle();
                            $buttonStyle = $option->getStyle();
                            $isWireNavigate = $option->isWireNavigate();
                        @endphp
                        @if ($option->isDivider() && $option->hasDividerOptions())
                            <div class="border-t border-b border-slate-100">
                                @foreach ($option->getDividerOptions() as $dividerOption)
                                    @php
                                        $dividerDisabled = $dividerOption->isDisabled($row);

                                        $event = [
                                            'event' => $dividerOption->getEvent($row),
                                            'disabled' => $dividerDisabled,
                                        ];

                                        $url = $option->getUrl($row);
                                        $target = $option->getTarget();

                                        $iconStyle = $dividerOption->getIconStyle();
                                        $buttonStyle = $option->getStyle();
                                        $isWireNavigate = $option->isWireNavigate();
                                    @endphp
                                    <x-simple-tables::dropdown-option
                                        title="{{ $dividerOption->getName() }}"
                                        icon="{{ $dividerOption->getIcon() ?? $defaultOptionIcon }}"
                                        :disabled="$optionDisabled || $dividerDisabled"
                                        :$event
                                        :$url
                                        :$target
                                        :$iconStyle
                                        :$buttonStyle
                                        :$themeDropdownOptionStyle
                                        :$isWireNavigate
                                    />
                                @endforeach
                            </div>
                        @else
                            <x-simple-tables::dropdown-option
                                title="{{ $option->getName() }}"
                                icon="{{ $option->getIcon() ?? $defaultOptionIcon }}"
                                :disabled="$optionDisabled"
                                :$event
                                :$url
                                :$target
                                :$iconStyle
                                :$buttonStyle
                                :$themeDropdownOptionStyle
                                :$isWireNavigate
                            />
                        @endif
                    @endforeach
                </div>
            </div>
        </template>
    @endif
</div>
