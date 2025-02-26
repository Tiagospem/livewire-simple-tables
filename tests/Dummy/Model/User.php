<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use TiagoSpem\SimpleTables\Tests\Dummy\Factories\UserFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property int $country_id
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 */
final class User extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'is_active',
        'country_id',
        'created_at',
        'updated_at',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function car(): HasOne
    {
        return $this->hasOne(Car::class);
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
