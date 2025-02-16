<tr class="{{ $trStyle }}">
    @foreach ($rowContent as $row)
        <td class="{{ $row->style }}">{!! $row->content !!}</td>
    @endforeach
</tr>
