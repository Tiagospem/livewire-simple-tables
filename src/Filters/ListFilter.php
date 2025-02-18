<?php

namespace TiagoSpem\SimpleTables\Filters;

use Illuminate\Support\Facades\View;
use TiagoSpem\SimpleTables\Interfaces\Filter;

abstract class ListFilter implements Filter
{
    protected ?string $defaultValue = null;

    protected ?string $label = null;

    protected ?string $placeholder = null;

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function render(): string
    {
        return View::make('simple-tables::filters.list', [
            'options' => $this->getOptions(),
            'filterId' => $this->getFilterId(),
            'label' => $this->getLabel(),
        ]);
    }
}
