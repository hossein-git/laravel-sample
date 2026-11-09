<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Role.
 *
 * @version June 5, 2020,
 *
 * @property string name
 * @property string description
 * @property string guard_name
 */
class Role extends \Spatie\Permission\Models\Role
{
    public const SUPER_ADMIN_ROLE_NAME = 'super-admin';

    public static array $rules = [
        'name' => 'required',
        'description' => 'required',
        'guard_name' => 'required',
    ];

    public $table = 'roles';
    public $timestamps = false;

    public $guard_name = 'employee';
    public $fillable = [
        'name',
        'description',
        'guard_name',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'guard_name' => 'string',
    ];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(
            Employee::class,
            config('permission.table_names')['model_has_roles'],
            'role_id',
            'model_id'
        );
    }

    public static function modelName(): string
    {
        return __('Role');
    }
}
