<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Employee\Models\Role;

return new class extends Migration {
    public function up(): void
    {
        $roles = [
            ['name' => Role::SUPER_ADMIN_ROLE_NAME, 'guard_name' => 'employee', 'description' => 'can do anything'],
            ['name' => 'employee', 'guard_name' => 'employee', 'description' => 'can edit and create posts , add new brands and categories , manage comments'],
            ['name' => 'author', 'guard_name' => 'employee', 'description' => 'can create posts , manage comments '],
        ];

        foreach ($roles as $role) {
            Role::query()->firstOrCreate(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']],
                ['description' => $role['description']],
            );
        }
    }

    public function down(): void
    {
        Role::query()
            ->where('guard_name', 'employee')
            ->whereIn('name', [Role::SUPER_ADMIN_ROLE_NAME, 'employee', 'author'])
            ->delete();
    }
};
