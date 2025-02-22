<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Datasource;

use Illuminate\Support\Collection;
use TiagoSpem\SimpleTables\Datasource\Resolvers\DataBuilderResolver;
use TiagoSpem\SimpleTables\Datasource\Resolvers\DataCollectionResolver;
use TiagoSpem\SimpleTables\Dto\TableData;
use TiagoSpem\SimpleTables\Exceptions\InvalidColumnException;
use TiagoSpem\SimpleTables\Interfaces\ProcessorInterface;
use TiagoSpem\SimpleTables\SimpleTableComponent;

final readonly class DataSourceResolver implements ProcessorInterface
{
    public function __construct(private SimpleTableComponent $component) {}

    /**
     * @throws InvalidColumnException
     */
    public function process(): TableData
    {
        $datasource = $this->component->datasource();
        $processor  = $this->resolveProcessor($datasource);

        return $processor->process();
    }

    public function resolveProcessor(mixed $datasource): DataBuilderResolver|DataCollectionResolver
    {
        if ($datasource instanceof Collection) {
            return new DataCollectionResolver($this->component);
        }

        return new DataBuilderResolver($this->component);
    }
}
