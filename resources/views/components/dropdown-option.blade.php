@props([
    'title' => '',
    'icon' => '',
    'disabled' => false,
    'event' => [],
    'iconStyle' => '',
    'buttonStyle' => '',
    'themeDropdownOptionStyle',
    'isWireNavigate' => false,
    'url' => '',
    'target' => '',
])

<div
    @class(['py-1', 'opacity-50 pointer-events-none' => $disabled])
    role="none"
>
    <a
        @if (filled($url)) href="{{ $url }}"
            target="{{ $target }}"
            @if ($isWireNavigate) wire:navigate @endif
    @else
        x-on:click="handleClick(@js($event))"
        @endif

        class="{{ mergeStyle($themeDropdownOptionStyle, $buttonStyle) }}"
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
