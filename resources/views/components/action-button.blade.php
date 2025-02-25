{{--@props(['actionBuilder', 'themeButtonActionClass', 'themeDropdownOptionClass', 'themeDropdownClass', 'row'])--}}

{{--@if ($actionBuilder->hasView())--}}
{{--    {!! $actionBuilder->getView($row) !!}--}}
{{--@else--}}
{{--    <div--}}
{{--        class="flex items-center justify-center"--}}
{{--        x-data="clickEvent"--}}
{{--    >--}}
{{--        @php--}}
{{--            $hasName = $actionBuilder->hasName();--}}
{{--            $disabled = $actionBuilder->isDisabled($row);--}}
{{--            $hasDropdown = $actionBuilder->hasDropdown();--}}

{{--            $dropdownOptions = $actionBuilder->getActionOptions();--}}

{{--            $hasIcon = $actionBuilder->hasIcon();--}}

{{--            $defaultOptionIcon = $actionBuilder->getDefaultOptionIcon();--}}

{{--            $buttonStyle = $actionBuilder->getStyle();--}}

{{--            $event = [--}}
{{--                'event' => $actionBuilder->getEvent($row),--}}
{{--                'hasDropdown' => $hasDropdown,--}}
{{--                'disabled' => $disabled,--}}
{{--            ];--}}

{{--            $url = $actionBuilder->getUrl($row);--}}
{{--            $target = $actionBuilder->getTarget();--}}
{{--            $isWireNavigate = $actionBuilder->isWireNavigate();--}}
{{--        @endphp--}}
{{--        <x-simple-tables::dropdown--}}
{{--            :$hasDropdown--}}
{{--            :$dropdownOptions--}}
{{--            :$defaultOptionIcon--}}
{{--            :$themeDropdownOptionClass--}}
{{--            :$themeDropdownClass--}}
{{--            :$row--}}
{{--        >--}}
{{--            <x-slot:actionButton>--}}
{{--                <a--}}
{{--                    @if (filled($url)) href="{{ $url }}"--}}
{{--                    target="{{ $target }}"--}}
{{--                    @if ($isWireNavigate) wire:navigate @endif--}}
{{--                @else--}}
{{--                    x-on:click="handleClick('{{ json_encode($event) }}')"--}}
{{--                    @endif--}}
{{--                    x-ref="dropdownButton"--}}
{{--                    @class([--}}
{{--                        'gap-x-1.5' => $hasName,--}}
{{--                        '!pointer-events-none !opacity-50' => $disabled,--}}
{{--                        $themeButtonActionClass,--}}
{{--                        $buttonStyle,--}}
{{--                    ])>--}}
{{--                    @if ($hasIcon)--}}
{{--                        <x-dynamic-component--}}
{{--                            :component="$actionBuilder->getIcon()"--}}
{{--                            @class([--}}
{{--                                '-mr-0.5' => $hasName,--}}
{{--                                $actionBuilder->getIconStyle(),--}}
{{--                            ])--}}
{{--                        />--}}
{{--                    @endif--}}
{{--                    <span>{{ $actionBuilder->getName() }}</span>--}}
{{--                </a>--}}
{{--            </x-slot:actionButton>--}}
{{--        </x-simple-tables::dropdown>--}}
{{--    </div>--}}
{{--@endif--}}
