@if($hasPagination)
    <div @class([$stickyStyle => $isStick])>
        <div class="{{ $style }}">
            {!! $paginator !!}
        </div>
    </div>
@endif
