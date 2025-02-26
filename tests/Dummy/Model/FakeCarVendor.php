<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use TiagoSpem\SimpleTables\Tests\Dummy\Factories\FakeCarVendorFactory;

/**
 * @property int $id
 * @property string $vendor
 * @property int $fake_car_id
 * @property string $created_at
 * @property string $updated_at
 */
final class FakeCarVendor extends Model
{
    /** @use HasFactory<FakeCarVendorFactory> */
    use HasFactory;

    protected $table = 'fake_cars_vendor';

    protected $fillable = [
        'id',
        'vendor',
        'fake_car_id',
        'created_at',
        'updated_at',
    ];

    public function fakeCar(): BelongsTo
    {
        return $this->belongsTo(FakeCar::class);
    }

    protected static function newFactory(): FakeCarVendorFactory
    {
        return FakeCarVendorFactory::new();
    }

}
