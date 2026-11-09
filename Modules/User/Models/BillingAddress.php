<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\BaseModel;
use Modules\User\Database\Factories\BillingAddressFactory;

class BillingAddress extends BaseModel
{
    protected $fillable = [
        'type',
        'name',
        'address',
        'city',
        'state',
        'country',
        'zip',
        'tax_type',
        'tax_id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function modelName(): string
    {
        return __('Billing Address');
    }

    protected static function newFactory(): BillingAddressFactory
    {
        return BillingAddressFactory::new();
    }
}

