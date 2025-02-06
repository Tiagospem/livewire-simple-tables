<?php

namespace TiagoSpem\SimpleTables\Facades;

use Illuminate\Support\Facades\Facade;
use TiagoSpem\SimpleTables\SimpleTableManager;
use TiagoSpem\SimpleTables\SimpleTableModifiers;
use TiagoSpem\SimpleTables\SimpleTablesStyleModifiers;

/**
 * @method static SimpleTableModifiers dataModifiers()
 * @method static SimpleTablesStyleModifiers styleModifiers()
 */
class SimpleTables extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SimpleTableManager::class;
    }
}
