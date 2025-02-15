<tr class="{{ $trStyle }}">
    @foreach($rowContent as $row)
        <td class="{{ $row->style }}">{!! $row->content !!}</td>
    @endforeach

    @if(filled($action))
        <td class="{{ $tdStyle }}">{!! $action !!}</td>
    @endif
</tr>
