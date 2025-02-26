<?php

declare(strict_types=1);

namespace TiagoSpem\SimpleTables\Tests\Dummy\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use TiagoSpem\SimpleTables\Tests\Dummy\Factories\CarVendorFactory;

/**
 * @property int $id
 * @property string $vendor
 * @property int $car_id
 * @property string $created_at
 * @property string $updated_at
 */
final class CarVendor extends Model
{
    /** @use HasFactory<CarVendorFactory> */
    use HasFactory;

    protected $table = 'cars_vendor';

    protected $fillable = [
        'id',
        'vendor',
        'car_id',
        'created_at',
        'updated_at',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    protected static function newFactory(): CarVendorFactory
    {
        return CarVendorFactory::new();
    }

}
