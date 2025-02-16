<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Blade;

use Illuminate\Support\Facades\View;
use TiagoSpem\SimpleTables\Action;
use TiagoSpem\SimpleTables\Column;
use TiagoSpem\SimpleTables\Dto\TableData;
use TiagoSpem\SimpleTables\Field;

final readonly class ContentParser
{
    /**
     * @param  array<string, array<string, string>|string>  $theme
     */
    public function __construct(private TableData $table, private mixed $row, private array $theme) {}

    /**
     * @return array<int, object>
     */
    public function mapFieldsWithContent(): array
    {
        $tdStyle = theme($this->theme, 'table.td');

        return collect($this->table->columns)
            ->filter(fn(Column $column): bool => $column->isVisible())
            ->map(function (Column $column) use ($tdStyle) {
                $handlers = $this->getColumnHandlers();

                foreach ($handlers as [$condition, $handler]) {
                    if ($condition($column)) {
                        return $handler($column);
                    }
                }

                return $this->getMutedData($column, $tdStyle, $this->table->mutations[$column->getRealKey()] ?? null);
            })
            ->all();
    }

    public function getMutedRowStyle(): string
    {
        return $this->table->tableRowStyle->getRowStyle($this->row, $this->theme);
    }

    private function getMutedData(Column $column, string $tdStyle, ?Field $mutation): object
    {
        $rowKey     = $column->getRowKey();
        $rowValue   = parserString(data_get($this->row, $rowKey));
        $fieldStyle = $this->resolveFieldStyle($mutation, $rowValue);
        $rowValue   = $this->resolveRowValue($mutation, $rowValue);

        return (object) [
            'content' => $rowValue,
            'style'   => mergeStyle($tdStyle, $fieldStyle),
        ];
    }

    private function resolveFieldStyle(?Field $field, mixed $rowValue): string
    {
        if ( ! $field instanceof Field) {
            return '';
        }

        $fieldStyles = [];

        foreach ($field->getStyleRules() as $styleMutation) {
            $mutationObject = $styleMutation->toObject();

            if (is_callable($mutationObject->callback)) {
                $fieldStyles[] = parserString(
                    $mutationObject->callback->__invoke(
                        $this->resolveCallbackParameter($mutationObject->parameterType, $rowValue),
                    ),
                );
            }
        }

        $fieldStyles[] = $field->getStyle();

        return implode(' ', array_filter($fieldStyles));
    }

    private function resolveRowValue(?Field $mutation, mixed $rowValue): mixed
    {
        if ($mutation instanceof Field) {
            $dataMutation = $mutation->getMutation()->toObject();

            if (is_callable($dataMutation->callback)) {
                return parserString($dataMutation->callback->__invoke($this->resolveCallbackParameter($dataMutation->parameterType, $rowValue)));
            }
        }

        return $rowValue;
    }

    private function resolveCallbackParameter(string $parameterType, mixed $rowValue): mixed
    {
        return isClassOrObject($parameterType) ? $this->row : $rowValue;
    }

    private function getActionColumn(Column $column): object
    {
        $tdStyle = theme($this->theme, 'table.td');
        $actions = $this->getActionsData();

        return (object) [
            'content' => View::make('simple-tables::table.partials.action-builder', $actions[$column->getColumnId()])->render(),
            'style'   => $tdStyle,
        ];
    }

    private function getBooleanColumn(Column $column): object
    {
        $tdStyle = theme($this->theme, 'table.td');

        return (object) [
            'content' => View::make('simple-tables::table.partials.boolean', [
                'value'   => (bool) data_get($this->row, $column->getRowKey()),
                'inverse' => $column->isInverse(),
                'size'    => theme($this->theme, 'table.boolean_icon'),
            ])->render(),
            'style'   => $tdStyle,
        ];
    }

    private function getToggleableColumn(Column $column): object
    {
        $tdStyle = theme($this->theme, 'table.td');

        return (object) [
            'content' => View::make('simple-tables::table.partials.toggleable', [
                'value'   => (bool) data_get($this->row, $column->getRowKey()),
                'inverse' => $column->isInverse(),
                //'size'    => theme($this->theme, 'table.boolean_icon'),
            ])->render(),
            'style'   => $tdStyle,
        ];
    }

    /**
     * @return array<int, array{0: callable, 1: callable}>
     */
    private function getColumnHandlers(): array
    {
        return [
            [
                fn(Column $column): bool => $column->getColumnType()->isAction(),
                fn(Column $column): object => $this->getActionColumn($column),
            ],
            [
                fn(Column $column): bool => $column->getColumnType()->isBoolean(),
                fn(Column $column): object => $this->getBooleanColumn($column),
            ],
            [
                fn(Column $column): bool => $column->getColumnType()->isToggle(),
                fn(Column $column): object => $this->getToggleableColumn($column),
            ],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function getActionsData(): array
    {
        return collect($this->table->actionBuilder->getActions())
            ->map(fn(Action $action): array => [
                'actionId'                 => $action->getActionId(),
                'actionBuilder'            => $action,
                'row'                      => $this->row,
                'hasName'                  => $action->hasName(),
                'hasView'                  => $action->hasView(),
                'hasIcon'                  => $action->hasIcon(),
                'hasDropdown'              => $action->hasDropdown(),
                'view'                     => $action->getView($this->row),
                'isDisabled'               => $action->isDisabled($this->row),
                'dropdownOptions'          => $action->getActionOptions(),
                'defaultOptionIcon'        => $action->getDefaultOptionIcon(),
                'buttonStyle'              => $action->getStyle(),
                'iconStyle'                => $action->getIconStyle(),
                'buttonIcon'               => $action->getIcon(),
                'buttonName'               => $action->getName(),
                'buttonUrl'                => $action->getUrl($this->row),
                'buttonTarget'             => $action->getTarget(),
                'buttonEvent'              => $action->getEvent($this->row),
                'themeActionButtonStyle'   => theme($this->theme, 'action.button'),
                'themeDropdownOptionStyle' => theme($this->theme, 'dropdown.option'),
                'themeDropdownStyle'       => theme($this->theme, 'dropdown.content'),
            ])
            ->keyBy(fn(array $item): string => $item['actionId'])
            ->all();
    }
}
