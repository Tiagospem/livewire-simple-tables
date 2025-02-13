<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Concerns;

use TiagoSpem\SimpleTables\Field;

final class Mutation
{
    /**
     * @var array<Field>
     */
    private array $fields = [];

    /**
     * @param  array<Field>  $fields
     */
    public function fields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return array<Field>
     */
    public function getFields(): array
    {
        return collect($this->fields)
            ->mapWithKeys(fn(Field $field) => [$field->getField() => $field])
            ->all();
    }
}
