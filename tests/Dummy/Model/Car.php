<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use TiagoSpem\SimpleTables\Tests\Dummy\Factories\CarFactory;

/**
 * @property int $id
 * @property string $model
 * @property string $color
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 */
final class Car extends Model
{
    /** @use HasFactory<CarFactory> */
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'id',
        'model',
        'color',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(CarVendor::class);
    }

    protected static function newFactory(): CarFactory
    {
        return CarFactory::new();
    }

}
