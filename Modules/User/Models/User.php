<?php

namespace Modules\User\Models;

use Cache;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Base\Traits\ReadableTimeStampTrait;
use Modules\User\Database\Factories\UserFactory;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

/**
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $role_id
 * @property string|null $verification_code
 * @property bool $verified
 * @property Carbon|null $trial_ends_at
 * @property string|null $mt5_account_number
 * @property string|null $mt4_account_number
 */
class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use SoftDeletes, Notifiable, HasRoles, InteractsWithMedia, HasFactory, ReadableTimeStampTrait;

    public const SUPER_USER_EMAIL = 'mt5@example.com';

    public const MEDIA_COLLECTION = 'covers';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'verification_code',
        'verified',
        'trial_ends_at',
        'mt5_account_number',
        'mt4_account_number',
    ];

    protected $hidden = [
        'api_key',
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function modelName(): string
    {
        return __('User');
    }

    public function getCachedPermissionNames(): Collection
    {
        $permissions = Cache::get($this->permissionsCacheKey());
        if ($permissions?->isNotEmpty()) {
            return $permissions;
        }

        $permissions = $this->getPermissionNames();
        Cache::remember($this->permissionsCacheKey(), now()->addDay(), static fn() => $permissions);

        return $permissions;
    }

    public function hasPermission(string $permission): bool
    {
        return $this->getCachedPermissionNames()->contains($permission);
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * set cover image to show in admin panel
     * it returns like: $this->getFirstMedia('covers')->getUrl('covers').
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion(self::MEDIA_COLLECTION)->width(180)->height(100);
    }

    /**
     *  add only one image for each users (as avatar).
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION)->useDisk('media')->singleFile();
    }

    public function billingAddress(): HasOne
    {
        return $this->hasOne(BillingAddress::class);
    }

    public function permissionsCacheKey(): string
    {
        return 'user-permissionNames-'. $this->id;
    }

    public function notificationConfigs(): HasMany
    {
        return $this->hasMany(NotificationConfig::class, 'user_id');
    }

    /**
     * Get the accounts associated with the user.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get the brokers associated with the user.
     */
    public function brokers(): BelongsToMany
    {
        return $this->belongsToMany(Broker::class);
    }
}
