@props([
    'title' => '',
    'icon' => '',
    'disabled' => false,
    'clickEvent' => [],
    'iconStyle' => '',
    'buttonStyle' => '',
])
<div
    @class(['py-1', 'opacity-50 pointer-events-none' => $disabled])
    role="none"
    {{ $attributes }}
>
    <a
        x-on:click="handleClick({{ json_encode($clickEvent) }})"
        class="{{ mergeClass(
            'hover:bg-gray-100 group flex items-center px-4 py-2 text-sm text-gray-700 cursor-pointer outline-none focus:outline-none',
            $buttonStyle,
        ) }}"
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
