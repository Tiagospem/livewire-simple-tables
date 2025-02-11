<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use Illuminate\Contracts\View\View;

trait HasPlaceholder
{
    public function placeholder(): View
    {
        return view('simple-tables::layout.skeleton', [
            'columns'    => count($this->columns()),
            'perPage'    => $this->perPage,
            'showSearch' => $this->showSearch(),
        ]);
    }
}
