<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TiagoSpem\SimpleTables\Tests\Dummy\Factories\CountryFactory;

/**
 * @property int $id
 * @property string $name
 */
final class Country extends Model
{
    /** @use HasFactory<CountryFactory> */
    use HasFactory;

    protected $table = 'countries';

    protected $fillable = [
        'id',
        'name',
    ];

    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }
}
