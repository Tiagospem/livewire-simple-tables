<?php

declare(strict_types=1);

use Illuminate\Contracts\View\View;
use TiagoSpem\SimpleTables\Traits\HasPlaceholder;

$hasPlaceholder = fn(): object => new class () {
    use HasPlaceholder;

    public int $perPage = 10;

    public function columns(): array
    {
        return ['id', 'name', 'email'];
    }

    public function showSearch(): bool
    {
        return true;
    }
};

it('returns the correct placeholder view', function () use ($hasPlaceholder): void {
    $component = $hasPlaceholder();

    $view = $component->placeholder();

    expect($view)->toBeInstanceOf(View::class)
        ->and($view->name())->toBe('simple-tables::table.skeleton')
        ->and($view->getData())->toMatchArray([
            'columns'    => 3,
            'perPage'    => 10,
            'showSearch' => true,
        ]);
});
