<?php

namespace TiagoSpem\SimpleTables;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use TiagoSpem\SimpleTables\Concerns\Base;
use TiagoSpem\SimpleTables\Concerns\TableStyle;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;

abstract class SimpleTableComponent extends Component
{
    use Base, TableStyle;

    /**
     * @throws InvalidColumnException
     */
    public function render(): View
    {
        return view('simple-tables::layout.table', [
            'data' => $this->getData(),
        ]);
    }
}
