<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Base\BaseModel;

/**
 * @property int $id
 * @property string $name
 * @property string $timezone
 */
class Broker extends BaseModel
{
    public static function modelName(): string
    {
        return __('Broker');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'timezone',
    ];

    /**
     * Get the accounts associated with the broker.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get the users associated with the broker.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
