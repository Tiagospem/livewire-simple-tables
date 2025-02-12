@props(['actionBuilder', 'themeButtonActionClass', 'themeDropdownOptionClass', 'themeDropdownClass', 'row'])

@if ($actionBuilder->hasView())
    {!! $actionBuilder->getView($row) !!}
@else
    <div
        class="flex items-center justify-center"
        x-data="clickEvent"
    >
        @php
            $hasName = $actionBuilder->hasName();
            $disabled = $actionBuilder->isDisabled($row);
            $hasDropdown = $actionBuilder->hasDropdown();

            $dropdownOptions = $actionBuilder->getActionOptions();

            $hasIcon = $actionBuilder->hasIcon();

            $defaultOptionIcon = $actionBuilder->getDefaultOptionIcon();

            $buttonStyle = $actionBuilder->getStyle();

            $clickEvent = [
                'url' => $actionBuilder->getUrl($row),
                'target' => $actionBuilder->getTarget(),
                'event' => $actionBuilder->getEvent($row),
                'hasDropdown' => $hasDropdown,
                'disabled' => $disabled,
            ];
        @endphp
        <x-simple-tables::dropdown
            :$hasDropdown
            :$dropdownOptions
            :$defaultOptionIcon
            :$themeDropdownOptionClass
            :$themeDropdownClass
            :$row
        >
            <x-slot:actionButton>
                <button
                    x-on:click="handleClick(@js($clickEvent))"
                    x-ref="dropdownButton"
                    @class([
                        'gap-x-1.5' => $hasName,
                        '!pointer-events-none !opacity-50' => $disabled,
                        $themeButtonActionClass,
                        $buttonStyle,
                    ])
                    type="button"
                >
                    @if ($hasIcon)
                        <x-dynamic-component
                            :component="$actionBuilder->getIcon()"
                            @class([
                                '-mr-0.5' => $hasName,
                                $actionBuilder->getIconStyle(),
                            ])
                        />
                    @endif
                    <span>{{ $actionBuilder->getName() }}</span>
                </button>
            </x-slot:actionButton>
        </x-simple-tables::dropdown>
    </div>
@endif
