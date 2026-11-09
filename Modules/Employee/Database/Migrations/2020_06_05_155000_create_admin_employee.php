<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration {
    public function up(): void
    {
        $employee = Employee::query()->firstOrCreate(
            ['email' => Employee::SUPER_EMPLOYEE_EMAIL],
            [
                'name' => 'John',
                'family' => 'Doe',
                'phone' => '+10000000000',
                'password' => bcrypt('password'),
            ]
        );

        $role = Role::query()
            ->where(['name' => Role::SUPER_ADMIN_ROLE_NAME, 'guard_name' => 'employee'])
            ->firstOrFail();

        $permissions = Permission::query()->where('guard_name', 'employee')->pluck('id', 'id')->all();
        $role->syncPermissions($permissions);

        $employee->assignRole([$role]);
    }

    public function down(): void
    {
        Employee::query()->where('email', Employee::SUPER_EMPLOYEE_EMAIL)->delete();
    }
};
