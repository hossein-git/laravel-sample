<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Employee\EmployeeServiceProvider;
use Modules\Trade\TradeServiceProvider;
use Modules\User\UserServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const array MODULES_SERVICE_PROVIDERS = [
        TradeServiceProvider::class,
        EmployeeServiceProvider::class,
        UserServiceProvider::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (self::MODULES_SERVICE_PROVIDERS as $serviceProvider) {
            $this->app->register($serviceProvider);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
