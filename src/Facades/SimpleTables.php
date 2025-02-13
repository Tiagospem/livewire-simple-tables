<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Facades;

use Illuminate\Support\Facades\Facade;
use TiagoSpem\SimpleTables\Concerns\ActionBuilder;
use TiagoSpem\SimpleTables\Concerns\BeforeSearch;
use TiagoSpem\SimpleTables\Concerns\Mutation;
use TiagoSpem\SimpleTables\Concerns\StyleModifiers;
use TiagoSpem\SimpleTables\SimpleTableManager;

/**
 * @method static Mutation mutation()
 * @method static StyleModifiers styleModifiers()
 * @method static ActionBuilder actionBuilder()
 * @method static BeforeSearch beforeSearch()
 */
final class SimpleTables extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SimpleTableManager::class;
    }
}
