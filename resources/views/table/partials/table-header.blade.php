<tr class="{{ $trStyle }}">
    @foreach ($columns as $column)
        <th class="{{ mergeStyle($thStyle, $column->getStyle()) }}">{{ $column->getTitle() }}</th>
    @endforeach

    @if($hasAction)
        <th class="{{ $thLastStyle }}">{{ $actionName }}</th>
    @endif
</tr>
