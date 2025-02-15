<tr class="{{ $trStyle }}">
    @foreach($rowContent as $row)
        <td class="{{ $row->style }}">{!! $row->content !!}</td>
    @endforeach

    @if(filled($action))
        <td class="{{ $actionStyle }}">{!! $action !!}</td>
    @endif
</tr>
