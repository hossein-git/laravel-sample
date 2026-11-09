<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\BaseModel;

class NotificationConfig extends BaseModel
{
    public const ON_ENTRY_TYPE = 'on-entry';
    public const ON_SIGNAL_TYPE = 'on-signal';

    protected $fillable = ['user_id', 'strategy_id', 'chart_namespace', 'status', 'type'];

    public static function modelName(): string
    {
        return __('Notification config');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
