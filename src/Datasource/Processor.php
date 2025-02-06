<?php

namespace TiagoSpem\SimpleTables\Datasource;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Datasource\Processors\DataBuilderProcessor;
use TiagoSpem\SimpleTables\Datasource\Processors\DataCollectionProcessor;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Exceptions\InvalidDatasetException;
use TiagoSpem\SimpleTables\Exceptions\InvalidParametersException;
use TiagoSpem\SimpleTables\SimpleTableComponent;

class Processor implements ProcessorInterface
{
    public function __construct(protected SimpleTableComponent $simpleTableComponent) {}

    /**
     * @throws InvalidColumnException
     * @throws InvalidDatasetException
     * @throws InvalidParametersException
     */
    public function process(): ?array
    {
        $datasource = $this->simpleTableComponent->datasource();

        return match (true) {
            $datasource instanceof Builder => (new DataBuilderProcessor($this->simpleTableComponent))->process(),
            $datasource instanceof Collection => (new DataCollectionProcessor($this->simpleTableComponent))->process(),
            default => throw new InvalidDatasetException
        };
    }
}
