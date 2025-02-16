@props([
    'sortIconStyle',
    'sortBy',
    'sortDirection',
    'column',
    'sortableIcons' => [
        'default' => 'chevron-up-down',
        'asc' => 'chevron-up',
        'desc' => 'chevron-down',
    ],
])

@php
    $icon = $sortBy === $column ? $sortableIcons[$sortDirection] : $sortableIcons['default'];
@endphp

<div
    class="cursor-pointer"
    wire:click="sortTableBy('{{ $column }}')"
>
    <x-dynamic-component
        :component="$icon"
        :class="$sortIconStyle"
    />
</div>
