<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Blade;

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
            ->map(function (Column $column) use ($tdStyle): object {
                $fieldKey = $column->getRealKey();

                $field = null;

                if (array_key_exists($fieldKey, $this->table->mutations)) {
                    $field = $this->table->mutations[$fieldKey];
                }

                return $this->getMutedData($column, $tdStyle, $field);
            })
            ->all();
    }

    public function getMutedRowStyle(): string
    {
        return $this->table->tableRowStyle->getRowStyle($this->row, $this->theme);
    }

    private function getMutedData(Column $column, string $tdStyle, ?Field $mutation): object
    {
        $rowKey  = $column->getRowKey();

        $rowValue = parserString(data_get($this->row, $rowKey));

        $fieldStyle = '';

        if ($mutation instanceof Field) {
            $styleMutations = $mutation->getStyleRules();
            $dataMutation   = $mutation->getMutation()->toObject();

            $fieldStyle = [];

            foreach ($styleMutations as $styleMutation) {
                $styleMutation = $styleMutation->toObject();

                $styleCallbackParameterCandidate = isClassOrObject($styleMutation->parameterType) ? $this->row : $rowValue;

                if (is_callable($styleMutation->callback)) {
                    $fieldStyle[] = parserString($styleMutation->callback->__invoke($styleCallbackParameterCandidate));
                }
            }

            $fieldStyle[] = $mutation->getStyle();

            $fieldStyle = implode(' ', $fieldStyle);

            $dataCallbackParameterCandidate = isClassOrObject($dataMutation->parameterType) ? $this->row : $rowValue;

            $rowValue = is_callable($dataMutation->callback) ?
                parserString($dataMutation->callback->__invoke($dataCallbackParameterCandidate)) : $rowValue;
        }

        return (object) [
            'content' => $rowValue,
            'style'   => mergeStyle($tdStyle, $fieldStyle),
        ];
    }
}
