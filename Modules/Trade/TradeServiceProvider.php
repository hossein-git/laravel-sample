<?php

namespace Modules\Trade;

use Illuminate\Support\ServiceProvider;

class TradeServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
