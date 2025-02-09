@props(['title' => '', 'icon' => '', 'disabled' => false])
<div
    @class(['py-1', 'opacity-50 pointer-events-none' => $disabled])
    role="none"
    {{ $attributes }}
>
    <a
        href="#"
        class="hover:bg-gray-100 group flex items-center px-4 py-2 text-sm text-gray-700"
        role="menuitem"
        tabindex="-1"
    >
        @if (filled($icon))
            <x-dynamic-component
                :component="$icon"
                class="mr-3 size-5 text-gray-400"
            />
        @endif

        {{ $title }}
    </a>
</div>
