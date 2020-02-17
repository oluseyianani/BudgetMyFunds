<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $this->withVersions("api.php", function ($namespace, $routes) {
            Route::prefix('api')->middleware('api')->namespace($namespace)->group($routes);
        });
    }

    /**
     * Versions the API rputes
     * @param  string   $file
     * @param  callable $callback
     * @return Collection
     */
    protected function withVersions($file, callable $callback)
    {
        return collect(["V1"])->map(function ($version) use ($file,$callback) {
            return call_user_func_array(
                $callback,
                [ sprintf("%s\%s", $this->namespace, $version), base_path(sprintf("routes/%s/%s", $version, $file))]
            );
        });
    }
}
