<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\V1\RegistrationCodeRepository;
use App\Repositories\V1\Interfaces\RegistrationCodeInterface;

class RegistrationCodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            RegistrationCodeInterface::class,
            RegistrationCodeRepository::class
        );
    }
}
