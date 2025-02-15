<div>
    @if ($hasView)
        {!! $view !!}
    @else
        <div class="flex items-center justify-center" x-data="clickEvent">
            @php
                $clickEvent = [
                    'url' => $buttonUrl,
                    'target' => $buttonTarget,
                    'event' => $buttonEvent,
                    'hasDropdown' => $hasDropdown,
                    'disabled' => $isDisabled,
                ];
            @endphp
            <x-simple-tables::dropdown
                :$hasDropdown
                :$dropdownOptions
                :$defaultOptionIcon
                :$themeDropdownOptionStyle
                :$themeDropdownStyle
                :$row
            >
                <x-slot:actionButton>
                    <button
                        x-on:click="handleClick(@js($clickEvent))"
                        x-ref="dropdownButton"
                        @class([
                            'gap-x-1.5' => $hasName,
                            '!pointer-events-none !opacity-50' => $isDisabled,
                            $themeActionButtonStyle,
                            $buttonStyle,
                        ])
                        type="button"
                    >
                        @if ($hasIcon)
                            <x-dynamic-component
                                :component="$buttonIcon"
                                @class([
                                    '-mr-0.5' => $hasName,
                                    $iconStyle
                                ])
                            />
                        @endif
                        <span>{{ $buttonName }}</span>
                    </button>
                </x-slot:actionButton>
            </x-simple-tables::dropdown>
        </div>
    @endif
</div>
