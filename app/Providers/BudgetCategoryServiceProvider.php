<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\V1\BudgetCategoryRepository;
use App\Repositories\V1\Interfaces\BudgetCategoryInterface;

class BudgetCategoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            BudgetCategoryInterface::class,
            BudgetCategoryRepository::class
        );
    }
}
