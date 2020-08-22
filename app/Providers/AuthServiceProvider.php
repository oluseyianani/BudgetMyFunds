<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        'App\Models\UserCategory' => 'App\Policies\UserCategoryPolicy',
        'App\Models\Goal' => 'App\Policies\GoalPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Passport::tokensExpireIn(now()->addDays(1));

        Passport::refreshTokensExpireIn(now()->addDays(1));

        Passport::personalAccessTokensExpireIn(now()->addDays(1));
    }
}
