<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Facades;

use Illuminate\Support\Facades\Facade;
use TiagoSpem\SimpleTables\SimpleTableManager;
use TiagoSpem\SimpleTables\SimpleTableModifiers;
use TiagoSpem\SimpleTables\SimpleTablesActionBuilder;
use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

/**
 * @method static SimpleTableModifiers dataModifiers()
 * @method static SimpleTablesStyleModifiers styleModifiers()
 * @method static SimpleTablesActionBuilder actionBuilder()
 */
final class SimpleTables extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SimpleTableManager::class;
    }
}
