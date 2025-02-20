<?php

namespace TiagoSpem\SimpleTables\Tests\Dummy\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 */
class FakeUser extends Model
{
    protected $table = 'fake_users';

    protected $fillable = [
        'id',
        'name',
        'email',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
