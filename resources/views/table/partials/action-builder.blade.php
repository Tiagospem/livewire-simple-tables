<div>
    @if ($hasView)
        {!! $view !!}
    @elseif(!$isHidden)
        <div
            class="flex items-center justify-center"
            x-data="clickEvent"
        >
            @php
                $clickEvent = [
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
                    <a
                        @if (filled($buttonUrl) && !$isDisabled) href="{{ $buttonUrl }}"
                            target="{{ $buttonTarget }}"
                            @if ($isWireNavigate) wire:navigate @endif
                        @elseif(filled($buttonEvent) && !$isDisabled)
                            x-on:click="handleClick(@js($event))"
                        @endif

                        @if($hasDropdown)
                            x-ref="dropdownButton"
                        @endif

                        @class([
                            'gap-x-1.5' => $hasName,
                            '!pointer-events-none !opacity-50 disabled' => $isDisabled,
                            $themeActionButtonStyle,
                            $buttonStyle,
                        ])>
                        @if ($hasIcon)
                            <x-dynamic-component
                                :component="$buttonIcon"
                                @class([
                                    '-mr-0.5' => $hasName,
                                    $iconStyle,
                                ])
                            />
                        @endif
                        <span>{{ $buttonName }}</span>
                    </a>
                </x-slot:actionButton>
            </x-simple-tables::dropdown>
        </div>
    @endif
</div>
