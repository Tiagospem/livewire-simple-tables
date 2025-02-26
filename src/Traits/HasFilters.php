<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Traits;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use TiagoSpem\SimpleTables\Interfaces\Filter;

trait HasFilters
{
    /**
     * @var array<string, mixed>
     */
    public array $filterValues     = [];
    protected bool $persistFilters = false;

    private ?string $tableCacheKey = null;

    /**
     * @throws Exception
     */
    public function bootHasFilters(): void
    {
        if ($this->persistFilters) {
            /** @var array<string, mixed> $cachedValues */
            $cachedValues       = Cache::get($this->getTableCacheKey(), []);
            $this->filterValues = $cachedValues;

            return;
        }

        foreach ($this->getFilters() as $filter) {
            $filterId = $filter->getFilterId();

            if ( ! array_key_exists($filterId, $this->filterValues)) {
                $this->filterValues[$filterId] = $filter->getDefaultValue();
            }
        }
    }

    /**
     * @throws Exception
     */
    public function updatedFilterValues(mixed $value, string $filterId): void
    {
        if ( ! array_key_exists($filterId, $this->filterValues)) {
            return;
        }

        if (null === $value || '' === $value) {
            unset($this->filterValues[$filterId]);
        } else {
            $this->filterValues[$filterId] = $value;
        }

        $this->filterValues = [...$this->filterValues];

        if ($this->persistFilters) {
            Cache::put($this->getTableCacheKey(), $this->filterValues);
        }
    }

    /**
     * @return Collection<int, Filter>
     */
    public function getFilters(): Collection
    {
        /** @var Collection<int, Filter> $filters */
        $filters = collect($this->filters())
            ->map(fn(string $filterClass) => app($filterClass))
            ->filter(fn($instance): bool => $instance instanceof Filter)
            ->values();

        foreach ($filters as $filter) {
            $filter->setFilterValues($this->filterValues);
        }

        return $filters;
    }

    public function getTotalFiltersSelected(): int
    {
        return collect($this->filterValues)->filter(fn($value): bool => null !== $value)->count();
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
    private function getTableCacheKey(): string
    {
        if (null !== $this->tableCacheKey) {
            return $this->tableCacheKey;
        }

        if ( ! Auth::check()) {
            throw new Exception('To use the cache feature, the user must be authenticated.');
        }

        $className           = mb_strtolower(str_replace('\\', '_', static::class));
        $this->tableCacheKey = sprintf('%s:%s:filters', $className, Auth::id());

        return $this->tableCacheKey;
    }
}
