<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Base\BaseModel;

/**
 * @property int $id
 * @property int $user_id
 * @property int $broker_id
 * @property int|null $status
 * @property Carbon|null $latest_activity
 * @property string $account_number
 * @property string|null $label
 * @property bool $is_live
 * @property float $max_margin_level
 * @property float $max_deposit_load
 * @property bool $is_main
 */
class Account extends BaseModel
{
    public const STATUS_ACTIVE = 1;

    public const DEFAULT_MARGIN_LEVEL_PERCENT = 65.0;

    public const DEFAULT_DEPOSIT_LOAD_PERCENT = 65.0;

    public static function modelName(): string
    {
        return __('Account');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'broker_id',
        'status',
        'latest_activity',
        'account_number',
        'label',
        'is_live',
        'max_margin_level',
        'max_deposit_load',
        'is_main',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latest_activity' => 'datetime',
        'status' => 'integer',
        'is_main' => 'boolean',
        'is_live' => 'boolean',
        'max_margin_level' => 'float',
        'max_deposit_load' => 'float',
    ];

    /**
     * Default attribute values for new models (aligns with migration defaults).
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'max_margin_level' => self::DEFAULT_MARGIN_LEVEL_PERCENT,
        'max_deposit_load' => self::DEFAULT_DEPOSIT_LOAD_PERCENT,
    ];

    /**
     * Get the user that owns the account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the broker that owns the account.
     */
    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

}
