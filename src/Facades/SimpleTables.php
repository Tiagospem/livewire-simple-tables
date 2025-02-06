<?php

namespace TiagoSpem\SimpleTables\Facades;

use Illuminate\Support\Facades\Facade;
use TiagoSpem\SimpleTables\SimpleTableManager;
use TiagoSpem\SimpleTables\SimpleTableModifiers;

/**
 * @method static SimpleTableModifiers modifiers()
 */
class SimpleTables extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SimpleTableManager::class;
    }
}
