<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\User\Database\Seeders\BrokerSeeder;
use Modules\User\Database\Seeders\NotificationConfigSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            BrokerSeeder::class,
            NotificationConfigSeeder::class,
        ]);
    }
}
