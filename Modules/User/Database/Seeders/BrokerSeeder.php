<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\User\Models\Broker;

class BrokerSeeder extends Seeder
{
    public const BACKTEST_BROKER_ID = 1;

    public function run(): void
    {
        if (Config::get('app.env') !== 'production') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Broker::query()->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // we create this in migration too
        Broker::query()->updateOrCreate(
            [
                'id' => self::BACKTEST_BROKER_ID,
                'name' => 'backtest-broker',
                'timezone' => 'Europe/Athens',
            ]
        );
    }
}
