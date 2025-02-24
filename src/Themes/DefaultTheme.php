<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Themes;

final class DefaultTheme implements ThemeInterface
{
    public function getStyles(): array
    {
        return [
            'table' => [
                'content'       => 'min-w-full divide-y divide-slate-200',
                'tbody'         => 'divide-y divide-slate-100 bg-white',
                'thead'         => 'bg-slate-50',
                'tr'            => 'bg-white even:bg-slate-50 border-t',
                'tr_header'     => 'bg-white',
                'th'            => 'whitespace-nowrap px-3 py-2 text-sm font-semibold text-slate-900 [&>:first-child]:flex [&>:first-child]:items-center [&>:first-child]:gap-2 [&>:first-child]:justify-between',
                'td'            => 'whitespace-nowrap px-3 py-2 text-sm text-slate-500',
                'td_no_records' => 'whitespace-nowrap px-3 py-4 text-sm text-slate-500 text-center',
                'sort_icon'     => 'size-4',
                'boolean_icon'  => 'size-6',
            ],
            'action' => [
                'button' => 'rounded-md bg-slate-600 px-2 py-1 text-sm font-semibold text-white shadow-sm hover:bg-slate-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600',
            ],
            'dropdown' => [
                'content' => 'z-40 w-56 fixed overflow-auto rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none',
                'option'  => 'hover:bg-slate-100 transition group flex items-center px-3 py-1.5 text-sm text-slate-700 cursor-pointer outline-none focus:outline-none',
            ],
            'pagination' => [
                'container' => 'mt-4 w-full',
                'sticky'    => 'sticky bottom-2 flex w-full',
            ],
        ];
    }
}
