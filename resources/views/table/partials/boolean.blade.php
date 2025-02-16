<div class="flex justify-center">
    @php
        $isCheck = $value ? !$inverse : $inverse;
        $icon = $isCheck ? 'simple-tables::svg.check-circle' : 'simple-tables::svg.x-circle';
        $color = $isCheck ? 'text-green-500' : 'text-red-500';
    @endphp

    <x-dynamic-component
        :component="$icon"
        class="{{ $size }} {{ $color }} block"
    />
</div>
