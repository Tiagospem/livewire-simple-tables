# Actions

Actions allow users to interact with individual rows in your tables. This guide explains how to add and customize actions in Livewire Simple Tables.

## Basic Actions Setup

To add actions to your table, you need to:

1. Add an action column to your table
2. Implement the `actionBuilder()` method

Here's a simple example:

```php
public function columns(): array
{
    return [
        Column::text('ID', 'id'),
        Column::text('Name', 'name'),
        Column::text('Email', 'email'),
        Column::action('actions', 'Actions'),
    ];
}

public function actionBuilder(): ActionBuilder
{
    return SimpleTables::actionBuilder()
        ->actions([
            Action::for('actions')
                ->button('svg.edit')
                ->href(fn (User $user) => route('users.edit', $user->id), true),
        ]);
}
```

This will add an edit button to each row that links to the user edit page.

## Action Types

Livewire Simple Tables supports several types of actions:

### Button Actions

Simple button actions that perform a single action:

```php
Action::for('actions')
    ->button('svg.edit')
    ->href(fn (User $user) => route('users.edit', $user->id), true)
```

### Dropdown Actions

Dropdown menus with multiple action options:

```php
Action::for('actions')
    ->button('svg.cog')
    ->dropdown([
        Option::add('Edit', 'svg.edit')
            ->href(fn (User $user) => route('users.edit', $user->id), true),
        Option::add('Delete', 'svg.trash')
            ->event('deleteUser', fn (User $user) => $user->id),
    ])
```

### Event Actions

Actions that trigger Livewire events:

```php
Action::for('actions')
    ->button('svg.trash')
    ->event('deleteUser', fn (User $user) => $user->id)
```

## Conditional Actions

You can conditionally show or hide actions based on permissions or row data:

```php
Action::for('actions')
    ->button('svg.cog')
    ->can('edit-users') // Check user permissions
    ->dropdown([
        Option::add('Activate', 'svg.check')
            ->hidden(fn (User $user) => $user->is_active) // Hide based on row data
            ->event('activateUser', fn (User $user) => $user->id),
        Option::add('Deactivate', 'svg.x')
            ->hidden(fn (User $user) => !$user->is_active) // Hide based on row data
            ->event('deactivateUser', fn (User $user) => $user->id),
    ])
```

## Action Groups with Dividers

You can group related actions with dividers:

```php
Action::for('actions')
    ->button('svg.cog')
    ->dropdown([
        Option::add('View', 'svg.eye')
            ->href(fn (User $user) => route('users.show', $user->id), true),
        
        Option::divider([
            Option::add('Edit', 'svg.edit')
                ->href(fn (User $user) => route('users.edit', $user->id), true),
            Option::add('Delete', 'svg.trash')
                ->event('deleteUser', fn (User $user) => $user->id),
        ])
    ])
```

## Complete Example

Here's a complete example of a table with various actions:

```php
public function actionBuilder(): ActionBuilder
{
    return SimpleTables::actionBuilder()
        ->actions([
            Action::for('actions')
                ->button('svg.cog')
                ->can('manage-users')
                ->dropdown([
                    Option::add('View Profile', 'svg.user')
                        ->href(fn (User $user) => route('users.show', $user->id), true),
                    Option::add('Edit', 'svg.edit')
                        ->href(fn (User $user) => route('users.edit', $user->id), true)
                        ->can('edit-users'),
                    
                    Option::divider([
                        Option::add('Activate', 'svg.check')
                            ->hidden(fn (User $user) => $user->is_active)
                            ->event('activateUser', fn (User $user) => $user->id)
                            ->can('activate-users'),
                        Option::add('Deactivate', 'svg.x')
                            ->hidden(fn (User $user) => !$user->is_active)
                            ->event('deactivateUser', fn (User $user) => $user->id)
                            ->can('deactivate-users'),
                    ]),
                    
                    Option::divider([
                        Option::add('Delete', 'svg.trash')
                            ->event('deleteUser', fn (User $user) => $user->id)
                            ->can('delete-users'),
                    ]),
                ]),
        ]);
}
```

## Next Steps

Now that you understand how to add actions to your tables, you might want to explore:

- [Bulk Actions](/components/bulk-actions) - Learn about bulk actions for multiple rows
- [Row Details](/usage/row-details) - Display detailed information for each row
- [Theming](/usage/theming) - Customize the appearance of your tables 
