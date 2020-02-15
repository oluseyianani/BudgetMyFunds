<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @SWG\Swagger(
 *     basePath="/api/v1",
 *     schemes={"http", "https"},
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="BudgetMyFunds API",
 *         description="BudgetMyFunds App API Documentation"
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
