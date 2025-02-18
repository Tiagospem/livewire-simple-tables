<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use TiagoSpem\SimpleTables\Interfaces\Filter;

trait HasFilters
{
    protected bool $persistFilters = false;

    public array $filterValues = [];

    private ?string $tableCacheKey = null;

    /**
     * @throws Exception
     */
    public function bootHasFilters(): void
    {
        if ($this->persistFilters) {
            $this->filterValues = Cache::get($this->getTableCacheKey(), []);

            return;
        }

        foreach ($this->getFilters() as $filter) {
            $filterId = $filter->getFilterId();

            if (! array_key_exists($filterId, $this->filterValues)) {
                $this->filterValues[$filterId] = $filter->getDefaultValue();
            }
        }
    }

    /**
     * @return array<int, string>
     */
    protected function filters(): array
    {
        return [];
    }

    /**
     * @throws Exception
     */
    public function updatedFilterValues(mixed $value, string $filterId): void
    {
        if (! array_key_exists($filterId, $this->filterValues)) {
            return;
        }

        $this->filterValues[$filterId] = $value;

        if ($this->persistFilters) {
            Cache::put($this->getTableCacheKey(), $this->filterValues);
        }
    }

    /**
     * @throws Exception
     */
    private function getTableCacheKey(): string
    {
        if ($this->tableCacheKey !== null) {
            return $this->tableCacheKey;
        }

        if (! auth()->check()) {
            throw new Exception('To use the cache feature, the user must be authenticated.');
        }

        $className = strtolower(str_replace('\\', '_', static::class));
        $this->tableCacheKey = sprintf('%s:%s:filters', $className, auth()->id());

        return $this->tableCacheKey;
    }

    /**
     * @return Collection<int, Filter>
     */
    public function getFilters(): Collection
    {
        return collect($this->filters())
            ->map(fn (string $filterClass) => app($filterClass))
            ->filter(fn ($instance): bool => $instance instanceof Filter)
            ->each(fn (Filter $filter) => $filter->setFilterValues($this->filterValues))
            ->values();
    }
}
