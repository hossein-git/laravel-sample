<?php

use Illuminate\Database\Migrations\Migration;
use Modules\User\Models\User;

return new class extends Migration {
    public function up(): void
    {
        User::query()->updateOrCreate(
            ['email' => User::SUPER_USER_EMAIL],
            [
                'name' => 'MT5',
                'email' => User::SUPER_USER_EMAIL,
                'password' => bcrypt('password'),
                'verified' => 1,
                'email_verified_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        User::query()->where('email', User::SUPER_USER_EMAIL)->delete();
    }
};
