<?php

namespace TiagoSpem\SimpleTables;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\Base;
use TiagoSpem\SimpleTables\Concerns\DataModifier;
use TiagoSpem\SimpleTables\Concerns\SearchModifier;
use TiagoSpem\SimpleTables\Concerns\StyleModifier;
use TiagoSpem\SimpleTables\Concerns\ThemeManager;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;

abstract class SimpleTableComponent extends Component
{
    use ActionBuilder, Base, DataModifier, SearchModifier, StyleModifier, ThemeManager;

    /**
     * @throws InvalidColumnException
     * @throws InvalidParametersException
     */
    public function render(): View
    {
        return view('simple-tables::layout.table', [
            'data' => $this->getData(),
            'theme' => $this->theme,
        ]);
    }
}
