@props(['sortIconStyle', 'sortBy', 'sortDirection', 'column', 'sortableIcons' => []])

@php
    $icon = $sortBy === $column ? $sortableIcons[$sortDirection] : $sortableIcons['default'];
@endphp

<div
    class="cursor-pointer"
    wire:loading.remove
    wire:target="sortTableBy('{{ $column }}')"
>
    <a wire:click="sortTableBy('{{ $column }}')">
        <x-dynamic-component
            :component="$icon"
            :class="$sortIconStyle"
        />
    </a>
</div>
<div
    wire:loading
    wire:target="sortTableBy('{{ $column }}')"
>
    <x-simple-tables::svg.spinner2 class="{{ $sortIconStyle }}" />
</div>
