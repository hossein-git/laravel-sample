<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;

return new class extends Migration {
    public function up(): void
    {
        Artisan::call('permission:cache-reset');

        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'content-list',
            'content-create',
            'content-edit',
            'content-delete',
            'content-destroy',

            'category-list',
            'category-create',
            'category-edit',
            'category-delete',

            'employee-list',
            'employee-create',
            'employee-edit',
            'employee-delete',
            'employee-destroy',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'user-destroy',

            'setting-list',
            'setting-delete',
            'setting-edit',

            'log-list',
            'log-delete',

            'see-dashboard',
        ];

        foreach ($permissions as $name) {
            Permission::query()->updateOrCreate(
                ['name' => $name, 'guard_name' => 'employee'],
            );
        }
    }

    public function down(): void
    {
        Permission::query()->where('guard_name', 'employee')->delete();
    }
};
