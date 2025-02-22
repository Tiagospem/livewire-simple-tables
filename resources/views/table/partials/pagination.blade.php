@if ($hasPagination)
    <div @class([$stickyStyle => $isStick])>
        <div class="{{ $style }}" aria-label="paginator">
            {!! $paginator !!}
        </div>
    </div>
@endif
