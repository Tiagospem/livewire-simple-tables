<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Themes;

final class DefaultTheme implements ThemeInterface
{
    public function getStyles(): array
    {
        return [
            'table' => [
                'content' => 'min-w-full divide-y divide-gray-300',
                'tbody' => 'divide-y divide-gray-200 bg-white',
                'thead' => 'bg-gray-50',
                'tr' => '',
                'th' => 'px-3 py-3.5 text-left text-sm font-semibold text-gray-900',
                'th_last' => 'relative py-3.5 pl-3 pr-4 sm:pr-6',
                'td' => 'whitespace-nowrap px-3 py-4 text-sm text-gray-500',
                'td_last' => 'relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6',
                'td_no_records' => 'whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center',
            ],
            'action' => [
                'button' => 'flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600',
            ],
            'dropdown' => [
                'content' => 'z-40 w-56 fixed overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none',
                'option' => 'hover:bg-gray-100 group flex items-center px-4 py-2 text-sm text-gray-700 cursor-pointer outline-none focus:outline-none',
            ],
            'pagination' => [],
        ];
    }
}
