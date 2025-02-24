<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TiagoSpem\SimpleTables\Tests\Dummy\Factories\FakeCountryFactory;

/**
 * @property int $id
 * @property string $name
 */
final class FakeCountry extends Model
{
    /** @use HasFactory<FakeCountryFactory> */
    use HasFactory;

    protected $table = 'fake_countries';

    protected $fillable = [
        'id',
        'name',
    ];

    protected static function newFactory(): FakeCountryFactory
    {
        return FakeCountryFactory::new();
    }
}
