<?php

namespace Modules\Trade\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\BaseModel;
use Modules\User\Models\User;

class Mql5Entry extends BaseModel
{
    protected $fillable = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
