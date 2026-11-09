<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\User\Models\NotificationConfig;

class NotificationConfigSeeder extends Seeder
{
    public function run(): void
    {
        if (Config::get('app.env') !== 'production') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            NotificationConfig::query()->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
