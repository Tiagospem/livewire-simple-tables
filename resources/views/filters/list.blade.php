<div>
    @if (filled($label))
        <label
            for="{{ $filterId }}"
            class="block text-sm/6 font-medium text-slate-900"
        >
            {{ $label }}
        </label>
    @endif
    <div class="mt-1">
        <select
            wire:model.live="filterValues.{{ $filterId }}"
            id="{{ $filterId }}"
            name="{{ $filterId }}"
            class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-slate-900 outline outline-1 -outline-offset-1 outline-slate-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-slate-600 sm:text-sm/6"
        >
            <option value="">All</option>
            @foreach ($options as $option)
                <option value="{{ $option[$valueKey] }}">{{ $option[$labelKey] }}</option>
            @endforeach
        </select>
    </div>
</div>
