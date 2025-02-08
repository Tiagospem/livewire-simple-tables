<div
    x-data="{
        search: '',
        get show() {
            return this.search.length > 0;
        },
        clear() {
            this.search = '';
    
            $wire.set('search', '');
        }
    }"
    class="relative w-full"
>
    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none">
        <x-simple-tables::svg.search class="pointer-events-none ml-3 size-5 self-center text-gray-400 sm:size-4" />
    </div>

    <input
        type="text"
        name="search"
        x-model="search"
        placeholder="{{ __('simple-tables::table.search') }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-md bg-white py-1.5 pl-10 pr-3 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:pl-9 sm:text-sm/6']) }}
    />

    <button
        x-cloak
        x-show="show"
        x-on:click="clear()"
        type="button"
        class="absolute inset-y-0 end-0 flex items-center pe-3"
    >
        <x-simple-tables::svg.x class="w-4 h-4" />
    </button>
</div>
