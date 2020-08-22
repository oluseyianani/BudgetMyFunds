<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\V1\AuthenticationRepository;
use App\Repositories\V1\Interfaces\AuthenticationInterface;

class AuthenticateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AuthenticationInterface::class,
            AuthenticationRepository::class
        );
    }
}
