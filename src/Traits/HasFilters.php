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

        $cacheKey = $this->getTableCacheKey();

        if ($this->persistFilters) {
            $this->updateCache();
        }

        $cached = Cache::get($cacheKey, []);

        $this->filterValues[$filterId] = array_key_exists($filterId, $cached)
            ? $cached[$filterId]
            : $value;
    }

    /**
     * @throws Exception
     */
    private function getTableCacheKey(): string
    {
        if (! auth()->check()) {
            throw new Exception('To use the cache feature, the user must be authenticated.');
        }

        $className = strtolower(str_replace('\\', '_', static::class));

        return sprintf('%s:%s:filters', $className, auth()->id());
    }

    /**
     * @throws Exception
     */
    private function updateCache(): void
    {
        Cache::put($this->getTableCacheKey(), $this->filterValues);
    }

    /**
     * @return Collection<int, Filter>
     */
    public function getFilters(): Collection
    {
        return collect($this->filters())
            ->map(fn (string $filterClass) => app($filterClass))
            ->filter(fn ($instance): bool => $instance instanceof Filter)
            ->each(function (Filter $filter): void {
                $filter->setFilterValues($this->filterValues);
            })
            ->values();
    }
}
