@if ($showSearch)
    <div class="flex justify-end mb-4 flex-1">
        <div class="w-[300px]">
            <x-simple-tables::input-search wire:model.live.debounce="search" />
        </div>
    </div>
@endif
