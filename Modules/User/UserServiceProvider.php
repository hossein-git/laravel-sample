<?php

namespace Modules\User;

use Illuminate\Support\ServiceProvider;
use Modules\User\Facades\UserFacade;
use Modules\User\Services\UserService;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        UserFacade::shouldProxyTo(UserService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
