<?php

namespace TiagoSpem\SimpleTables;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use TiagoSpem\SimpleTables\Concerns\Base;
use TiagoSpem\SimpleTables\Concerns\SearchModifier;
use TiagoSpem\SimpleTables\Concerns\ThemeManager;
use TiagoSpem\SimpleTables\Concerns\ValueModifier;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidDatasetException;

abstract class SimpleTableComponent extends Component
{
    use Base, SearchModifier, ThemeManager, ValueModifier;

    /**
     * @throws InvalidColumnException|InvalidDatasetException
     */
    public function render(): View
    {
        return view('simple-tables::layout.table', [
            'data' => $this->getData(),
            'theme' => $this->theme,
        ]);
    }
}
