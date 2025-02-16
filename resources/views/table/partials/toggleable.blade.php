<div class="flex justify-center">
    <button
        x-data="{ enabled: @js($value) }"
        x-on:click="enabled = !enabled"
        :class="enabled ? 'bg-indigo-600' : 'bg-gray-200'"
        type="button"
        class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
        role="switch"
        :aria-checked="enabled"
    >
        <span class="sr-only">Use setting</span>
        <span
            class="pointer-events-none relative inline-block size-4 transform rounded-full bg-white ring-0 shadow-sm transition duration-200 ease-in-out"
            :class="enabled ? 'translate-x-4' : 'translate-x-0'"
        >
            <span
                x-show="!enabled"
                x-transition:enter="opacity-100 duration-200 ease-in"
                x-transition:leave="opacity-0 duration-100 ease-out"
                class="absolute inset-0 flex size-full items-center justify-center"
                aria-hidden="true"
            >
                <svg
                    class="size-2.5 text-gray-400"
                    fill="none"
                    viewBox="0 0 12 12"
                >
                    <path
                        d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </span>
            <span
                x-show="enabled"
                x-transition:enter="opacity-100 duration-200 ease-in"
                x-transition:leave="opacity-0 duration-100 ease-out"
                class="absolute inset-0 flex size-full items-center justify-center"
                aria-hidden="true"
            >
                <svg
                    class="size-2.5 text-indigo-600"
                    fill="currentColor"
                    viewBox="0 0 12 12"
                >
                    <path
                        d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
                    />
                </svg>
            </span>
        </span>
    </button>

</div>
