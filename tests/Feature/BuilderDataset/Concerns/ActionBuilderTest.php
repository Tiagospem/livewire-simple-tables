<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

use TiagoSpem\SimpleTables\Action;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Enum\Target;
use TiagoSpem\SimpleTables\Facades\SimpleTables;
use TiagoSpem\SimpleTables\SimpleTableComponent;
use TiagoSpem\SimpleTables\Tests\Dummy\Model\FakeUser;
use TiagoSpem\SimpleTables\Themes\DefaultTheme;

beforeEach(function (): void {
    FakeUser::factory(2)
        ->state(new Sequence(
            ['is_active' => true, 'name' => 'John Doe'],
            ['is_active' => false, 'name' => 'Jane Doe'],
        ))
        ->create();
});

it('should be able to create an action builder as a simple button', function (): void {
    $dynamicComponent = new class () extends SimpleTableComponent {
        public function columns(): array
        {
            return [
                Column::text('Name', 'name'),
                Column::action('action', 'action column'),
            ];
        }

        public function actionBuilder(): ActionBuilder
        {
            return SimpleTables::actionBuilder()
                ->actions([
                    Action::for('action')
                        ->button(name: 'act button', href: 'https://example.com', wireNavigate: true, target: Target::BLANK),
                ]);
        }

        public function datasource(): Builder
        {
            return FakeUser::query();
        }
    };

    livewire($dynamicComponent::class)
        ->assertSee(['act button', 'action column'])
        ->assertSeeHtml('href="https://example.com"')
        ->assertSeeHtml('target="_blank"')
        ->assertSeeHtml('wire:navigate')
        ->assertOk();
});

it('should be able to create an action button with a custom style', function (): void {
    $dynamicComponent = new class () extends SimpleTableComponent {
        public function columns(): array
        {
            return [
                Column::text('Name', 'name'),
                Column::action('action', 'action column'),
            ];
        }

        public function actionBuilder(): ActionBuilder
        {
            return SimpleTables::actionBuilder()
                ->actions([
                    Action::for('action')
                        ->button(name: 'act button')
                        ->buttonStyle('button-with-custom-style'),
                ]);
        }

        public function datasource(): Builder
        {
            return FakeUser::query();
        }
    };

    $theme = (new DefaultTheme())->getStyles();

    $themeButtonStyle = theme($theme, 'action.button');

    $expectedButtonStyle = mergeStyle($themeButtonStyle, 'button-with-custom-style');

    livewire($dynamicComponent::class)
        ->assertSee(['act button', 'action column'])
        ->assertSeeHtml($expectedButtonStyle)
        ->assertOk();
});

it('should be able to disable action button', function (): void {
    $dynamicComponent = new class () extends SimpleTableComponent {
        public function columns(): array
        {
            return [
                Column::text('Name', 'name'),
                Column::action('action', 'action column'),
            ];
        }

        public function actionBuilder(): ActionBuilder
        {
            return SimpleTables::actionBuilder()
                ->actions([
                    Action::for('action')
                        ->button(name: 'act button', href: 'https://example.com', wireNavigate: true, target: Target::BLANK)
                        ->disabled(),
                ]);
        }

        public function datasource(): Builder
        {
            return FakeUser::query();
        }
    };

    $theme = (new DefaultTheme())->getStyles();

    $themeButtonStyle = theme($theme, 'action.button');

    $expectedButtonStyle = mergeStyle('!pointer-events-none !opacity-50 disabled', $themeButtonStyle);

    livewire($dynamicComponent::class)
        ->assertSeeHtml($expectedButtonStyle)
        ->assertDontSeeHtml('href="https://example.com"')
        ->assertDontSeeHtml('wire:navigate')
        ->assertDontSeeHtml('target="_blank"')
        ->assertOk();
});

it('should be able to disable action button using callback', function (): void {
    $dynamicComponent = new class () extends SimpleTableComponent {
        public function columns(): array
        {
            return [
                Column::text('Name', 'name')->searchable(),
                Column::action('action', 'action column'),
            ];
        }

        public function actionBuilder(): ActionBuilder
        {
            return SimpleTables::actionBuilder()
                ->actions([
                    Action::for('action')
                        ->button(name: 'act button', href: 'https://example.com', wireNavigate: true, target: Target::BLANK)
                        ->disabled(fn(FakeUser $user): bool => ! $user->is_active),
                ]);
        }

        public function datasource(): Builder
        {
            return FakeUser::query();
        }
    };

    $theme = (new DefaultTheme())->getStyles();

    $themeButtonStyle = theme($theme, 'action.button');

    $expectedButtonStyle = mergeStyle('!pointer-events-none !opacity-50 disabled', $themeButtonStyle);

    livewire($dynamicComponent::class)
        ->set('search', 'Jane Doe')
        ->assertSeeHtml($expectedButtonStyle)
        ->assertDontSeeHtml('href="https://example.com"')
        ->assertDontSeeHtml('wire:navigate')
        ->assertDontSeeHtml('target="_blank"')
        ->set('search', 'John Doe')
        ->assertSee('act button')
        ->assertSeeHtml('href="https://example.com"')
        ->assertSeeHtml('wire:navigate')
        ->assertSeeHtml('target="_blank"')
        ->assertSeeHtml('class="flex items-center justify-center"')
        ->assertOk();
});

it('should be able to hidde action button', function (): void {
    $dynamicComponent = new class () extends SimpleTableComponent {
        public function columns(): array
        {
            return [
                Column::text('Name', 'name'),
                Column::action('action', 'action column'),
            ];
        }

        public function actionBuilder(): ActionBuilder
        {
            return SimpleTables::actionBuilder()
                ->actions([
                    Action::for('action')
                        ->button(name: 'act button', href: 'https://example.com', wireNavigate: true, target: Target::BLANK)
                        ->hidden(),
                ]);
        }

        public function datasource(): Builder
        {
            return FakeUser::query();
        }
    };

    livewire($dynamicComponent::class)
        ->assertDontSee('act button')
        ->assertDontSeeHtml('href="https://example.com"')
        ->assertDontSeeHtml('wire:navigate')
        ->assertDontSeeHtml('target="_blank"')
        ->assertDontSeeHtml('class="flex items-center justify-center"')
        ->assertOk();
});

it('should be able to hidde action button using callback', function (): void {
    $dynamicComponent = new class () extends SimpleTableComponent {
        public function columns(): array
        {
            return [
                Column::text('Name', 'name')->searchable(),
                Column::action('action', 'action column'),
            ];
        }

        public function actionBuilder(): ActionBuilder
        {
            return SimpleTables::actionBuilder()
                ->actions([
                    Action::for('action')
                        ->button(name: 'act button', href: 'https://example.com', wireNavigate: true, target: Target::BLANK)
                        ->hidden(fn(FakeUser $user): bool => ! $user->is_active),
                ]);
        }

        public function datasource(): Builder
        {
            return FakeUser::query();
        }
    };

    livewire($dynamicComponent::class)
        ->set('search', 'Jane Doe')
        ->assertDontSee('act button')
        ->assertDontSeeHtml('href="https://example.com"')
        ->assertDontSeeHtml('wire:navigate')
        ->assertDontSeeHtml('target="_blank"')
        ->assertDontSeeHtml('class="flex items-center justify-center"')
        ->set('search', 'John Doe')
        ->assertSee('act button')
        ->assertSeeHtml('href="https://example.com"')
        ->assertSeeHtml('wire:navigate')
        ->assertSeeHtml('target="_blank"')
        ->assertSeeHtml('class="flex items-center justify-center"')
        ->assertOk();
});

it('create test for view button', function (): void {})->todo();

it('create test for permission button', function (): void {})->todo();

it('create test for dropdown button', function (): void {})->todo();

it('create test for align style when has name', function (): void {})->todo();

it('create test for align style when has name and icon', function (): void {})->todo();

it('create test for validate icon on button', function (): void {})->todo();
