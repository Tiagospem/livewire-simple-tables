<?php

namespace TiagoSpem\SimpleTables\Filters;

use Illuminate\Support\Facades\View;
use TiagoSpem\SimpleTables\Interfaces\Filter;

abstract class ListFilter implements Filter
{
    protected string $valueKey = 'value';

    protected string $labelKey = 'label';

    /**
     * @var array<string, mixed>
     */
    protected array $filterValues = [];

    protected ?string $defaultValue = null;

    protected mixed $selectedValue = null;

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

    public function getSelectedValue(): mixed
    {
        return $this->selectedValue;
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public function setFilterValues(array $values): void
    {
        $this->filterValues = $values;

        $this->selectedValue = $this->getFilterValueById($this->getFilterId());
    }

    public function getFilterValueById(string $filterId): mixed
    {
        return $this->filterValues[$filterId] ?? null;
    }

    public function render(): string
    {
        return View::make('simple-tables::filters.list', [
            'options' => $this->getOptions(),
            'filterId' => $this->getFilterId(),
            'label' => $this->getLabel(),
            'valueKey' => $this->valueKey,
            'labelKey' => $this->labelKey,
        ])->render();
    }
}
