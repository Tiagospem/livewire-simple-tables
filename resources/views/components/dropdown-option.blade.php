@props([
    'title' => '',
    'icon' => '',
    'disabled' => false,
    'clickEvent' => [],
    'iconStyle' => '',
    'buttonStyle' => '',
    'themeDropdownOptionClass',
])
<div
    @class(['py-1', 'opacity-50 pointer-events-none' => $disabled])
    role="none"
    {{ $attributes }}
>
    <a
        x-on:click="handleClick(@js($clickEvent))"
        @class([mergeClass($themeDropdownOptionClass, $buttonStyle)])
        role="menuitem"
        tabindex="-1"
    >
        @if (filled($icon))
            <x-dynamic-component
                :component="$icon"
                @class(['mr-3 size-5 text-gray-400', $iconStyle])
            />
        @endif

        {{ $title }}
    </a>
</div>
