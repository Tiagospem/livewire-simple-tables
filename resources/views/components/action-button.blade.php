@props(['actionBuilder', 'themeButtonActionClass', 'row'])

@if ($actionBuilder->hasActionView())
    {!! $actionBuilder->getActionView($row) !!}
@elseif($actionBuilder->hasActionButton())
    <div x-data="clickEvent">
        @php
            $hasButtonName = $actionBuilder->hasButtonName();
            $actionDisabled = $actionBuilder->getIsActionDisabled($row);

            $hasDropdown = $actionBuilder->hasDropdown();

            $actionOptions = $actionBuilder->getActionOptions();

            $hasIcon = $actionBuilder->hasIcon();

            $defaultDropdownOptionIcon = $actionBuilder->getDefaultDropdownOptionIcon();

            $clickEvent = [
                'actionUrl' => $actionBuilder->getActionUrl($row),
                'actionTarget' => $actionBuilder->getActionUrlTarget(),
                'eventName' => $actionBuilder->getEventName(),
                'eventParams' => $actionBuilder->getEventParams($row),
                'hasDropdown' => $hasDropdown,
                'disabled' => $actionDisabled,
            ];
        @endphp
        <x-simple-tables::dropdown
            :$hasDropdown
            :$actionOptions
            :$defaultDropdownOptionIcon
        >
            <x-slot:actionButton>
                <button
                    x-on:click="handleClick({{ json_encode($clickEvent) }})"
                    x-ref="dropdownButton"
                    @class([
                        'gap-x-1.5' => $hasButtonName,
                        '!pointer-events-none !opacity-50' => $actionDisabled,
                        $themeButtonActionClass,
                    ])
                    type="button"
                >
                    @if ($hasIcon)
                        <x-dynamic-component
                            :component="$actionBuilder->getButtonIcon()"
                            @class([
                                '-mr-0.5' => $hasButtonName,
                                $actionBuilder->getActionIconStyle(),
                            ])
                        />
                    @endif
                    <span>{{ $actionBuilder->getButtonName() }}</span>
                </button>
            </x-slot:actionButton>
        </x-simple-tables::dropdown>
    </div>
@endif
