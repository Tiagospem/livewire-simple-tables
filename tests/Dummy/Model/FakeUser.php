<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use TiagoSpem\SimpleTables\Tests\Dummy\Factories\FakeUserFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $country_id
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 */
final class FakeUser extends Model
{
    /** @use HasFactory<FakeUserFactory> */
    use HasFactory;

    protected $table = 'fake_users';

    protected $fillable = [
        'id',
        'name',
        'email',
        'is_active',
        'country_id',
        'created_at',
        'updated_at',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(FakeCountry::class);
    }

    public function car(): HasOne
    {
        return $this->hasOne(FakeCar::class);
    }

    protected static function newFactory(): FakeUserFactory
    {
        return FakeUserFactory::new();
    }
}
