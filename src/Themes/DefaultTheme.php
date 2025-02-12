<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Themes;

final class DefaultTheme implements ThemeInterface
{
    public function getStyles(): array
    {
        return [
            'table' => [
                'content'       => 'min-w-full divide-y divide-gray-200',
                'tbody'         => 'divide-y divide-gray-100 bg-white',
                'thead'         => 'bg-gray-50',
                'tr'            => 'even:bg-gray-50',
                'th'            => 'px-3 py-2 text-left text-sm font-semibold text-gray-900',
                'th_last'       => 'relative text-left px-3 py-2',
                'td'            => 'whitespace-nowrap px-3 py-2 text-sm text-gray-500',
                'td_last'       => 'px-3 py-2 text-sm text-gray-500',
                'td_no_records' => 'whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center',
            ],
            'action' => [
                'button' => 'rounded-md bg-indigo-600 px-2 py-1 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600',
            ],
            'dropdown' => [
                'content' => 'z-40 w-56 fixed overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none',
                'option'  => 'hover:bg-gray-100 group flex items-center px-4 py-2 text-sm text-gray-700 cursor-pointer outline-none focus:outline-none',
            ],
            'pagination' => [],
        ];
    }
}
