<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\V1\BudgetSubCategoryRepository;
use App\Repositories\V1\Interfaces\BudgetSubCategoryInterface;


class BudgetSubCategoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            BudgetSubCategoryInterface::class,
            BudgetSubCategoryRepository::class
        );
    }
}
