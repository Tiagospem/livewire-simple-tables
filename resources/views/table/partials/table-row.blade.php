<tr class="{{ $trStyle }}">
    @foreach ($rowContent as $row)
        <td class="{{ $row->style }}">
            @if($loop->first)
                <a>opem</a>
            @endif
            {!! $row->content !!}
        </td>
    @endforeach
</tr>

{{--<template x-if="false">--}}
{{--    <tr>--}}
{{--        <td colspan="9999" class="p-4 border-y">xxx</td>--}}
{{--    </tr>--}}
{{--</template>--}}
