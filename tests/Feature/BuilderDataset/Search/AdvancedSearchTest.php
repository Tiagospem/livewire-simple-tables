<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;

$dynamicComponent = fn(): SimpleTableComponent => new class () extends SimpleTableComponent {
    private Builder $datasetTest;

    private array $columnsTest = [];

    public function mount(Builder $dataset, array $columns, array $columnsToSearch = []): void
    {
        $this->datasetTest     = $dataset;
        $this->columnsTest     = $columns;
        $this->columnsToSearch = $columnsToSearch;
    }

    public function columns(): array
    {
        return $this->columnsTest;
    }

    public function datasource(): Builder
    {
        return $this->datasetTest;
    }
};

it('should be able to search using join', function () use ($dynamicComponent): void {
    FakeUser::factory(10)->create();

    $dataset = FakeUser::query();

    $columns = [
        Column::text('Id', 'id'),
        Column::text('Email', 'email')->searchable(),
    ];

    livewire($dynamicComponent()::class, [
        'dataset' => $dataset,
        'columns' => $columns,
    ])->assertOk();
});
