<?php

namespace Modules\Employee\Models;

use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Modules\Employee\Notifications\EmployeeResetPassword;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable implements FilamentUser, HasMedia
{
    use HasRoles, InteractsWithMedia, Notifiable, SoftDeletes;

    public const SUPER_EMPLOYEE_EMAIL = 'admin@example.com';

    public const MEDIA_COLLECTION = 'employee-covers';

    public $table = 'employees';

    protected $guard_name = 'employee';

    public $fillable = [
        'email',
        'phone',
        'name',
        'family',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'id' => 'integer',
        'email' => 'string',
        'phone' => 'string',
        'name' => 'string',
        'family' => 'string',
        'password' => 'string',
    ];

    public static function modelName(): string
    {
        return __('Employee');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * CHECK USER ACTIVITY WITH MIDDLEWARE ADN THIS FUNC.
     */
    public function isOnline(): bool
    {
        return Cache::has('user-is-online'.$this->id);
    }

    /**
     * get created at in diffForHumans forma.
     */
    public function getFormattedCreatedAt(): string
    {
        return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']))->toDateTimeString();
    }

    /**
     * set cover image to show in admin panel
     * it returns like: $this->getFirstMedia('employee-covers')->getUrl('employee-covers').
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion(self::MEDIA_COLLECTION)->width(180)->height(100);
    }

    /**
     *  add only one image for each employee (as avatar).
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_COLLECTION)->useDisk('media')->singleFile();
    }

    /**
     * send forget password link.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new EmployeeResetPassword($token));
    }

    /**
     * get updated at in diffForHumans forma.
     */
    public function getUpdatedAtAttribute(): string
    {
        return Carbon::createFromTimeStamp(strtotime($this->attributes['updated_at']))->diffForHumans();
    }
}
