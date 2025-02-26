<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use TiagoSpem\SimpleTables\Tests\Dummy\Factories\FakeCarFactory;

/**
 * @property int $id
 * @property string $model
 * @property string $color
 * @property int $fake_user_id
 * @property string $created_at
 * @property string $updated_at
 */
final class FakeCar extends Model
{
    /** @use HasFactory<FakeCarFactory> */
    use HasFactory;

    protected $table = 'fake_cars';

    protected $fillable = [
        'id',
        'model',
        'color',
        'fake_user_id',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(FakeUser::class);
    }

    public function vendor(): HasOne
    {
        return $this->hasOne(FakeCarVendor::class);
    }

    protected static function newFactory(): FakeCarFactory
    {
        return FakeCarFactory::new();
    }

}
